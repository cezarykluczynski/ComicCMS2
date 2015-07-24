<?php
/**
 * This class will gather settings from core settings, templates and plugins, and store then in database.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */
namespace Settings\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use RecursiveRegexIterator;
use Settings\Entity\Setting;
use PhpParser;

class ManifestService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $settingsCollection;
    protected $settings;
    protected $flattenSettings = [];
    protected $errors = [];
    protected $descriptions = [];

    /**
     * @codeCoverageIgnore
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Setter for settings parsed by {@link \Settings\Service\Settings::onDispatchSettings}.
     *
     * @param array $settings Settings as could be received from service locator, a multidimensional array.
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Setter for settings collection.
     *
     * @param array $settingsCollection Collection of all settings as Doctrine entities.
     */
    public function setSettingsCollection($settingsCollection)
    {
        $this->settingsCollection = $settingsCollection;

        /** Also a flatten list of setting is required to filter out doubles before inserting into database. */
        foreach($settingsCollection as $setting)
        {
            $this->flattenSettings[$setting->name] = $setting->value;
        }
    }

    /**
     * Getter for settings.
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Getter for settings collection.
     *
     * @return \Settings\Entity\Setting[]
     */
    public function getSettingsCollection()
    {
        return $this->settingsCollection;
    }

    /**
     * Getter for flatten settings.
     *
     * @return array
     */
    public function getFlattenSettings()
    {
        return $this->flattenSettings;
    }

    /**
     * Getter for settings descriptions.
     *
     * @return array
     */
    public function getDescriptions()
    {
        return $this->descriptions;
    }

    /**
     * Discovers new settings added by templates and plugins.
     *
     * @return void
     */
    public function discover()
    {
        /** Discard old errors. */
        $this->errors = [];

        $coreSettings = $this->discoverCoreSettings();
        $templatesSettings = $this->discoverTemplatesSettings();
        $pluginsSettings = $this->discoverPluginsSettings();

        /** @var \Doctrine\ORM\EntityManager */
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $isSettingsIntersection = $this->isSettingsIntersection([
            'coreSettings' => $coreSettings,
            'templatesSettings' => $templatesSettings,
            'pluginsSettings' => $pluginsSettings,
        ]);

        if (!$isSettingsIntersection)
        {
            /** @var array Merged settings from all sources. */
            $settings = array_merge($coreSettings, $templatesSettings, $pluginsSettings);

            /** Create entities. */
            foreach($settings as $name => $value)
            {
                /** @var \Settings\Entity\Setting */
                $setting = new Setting;

                $setting->name = $name;
                $setting->value = $value;

                $em->persist($setting);
            }

            /** Save everything. */
            $em->flush();
        }

        $settings = $em->getRepository('Settings\Entity\Setting')->findAll();

        foreach($settings as $setting)
        {
            $this->descriptions[$setting->name]['id'] = $setting->id;
        }
    }

    /**
     * Validates that the settings doesn't intersect.
     *
     * @param  array   $settings Array containing 'coreSettings', 'templateSettings', and 'pluginsSettings' keys.
     * @return boolean           True if settings are intersecting, false otherwise.
     */
    public function isSettingsIntersection($settings)
    {
        if (!empty(array_intersect_key($settings['coreSettings'], $settings['templatesSettings'])))
        {
            /** Report conflict: core settings are more important than templates settings. */
            $this->error('Some templates define settings keys that were already defined by core application.');
        }
        elseif (!empty(array_intersect_key($settings['coreSettings'], $settings['pluginsSettings'])))
        {
            /** Report conflict: core settings are more important than plugins settings. */
            $this->error('Some plugins define settings keys that were already defined by core application.');
        }
        elseif (!empty(array_intersect_key($settings['templatesSettings'], $settings['pluginsSettings'])))
        {
            /** Report conflict: templates nad plugins should not share key names. */
            $this->error('Both plugins and templates define the same settings keys.');
        }
        else
        {
            return false;
        }

        return true;
    }

    /**
     * Returns an array of parsing errors.
     *
     * @return array
     */
    public function getParsingErrors()
    {
        $this->translator = $this->getServiceLocator()->get('translator');

        /** @var array */
        $messages = [];

        /** Build messages list. */
        foreach($this->errors as $error)
        {
            $messages[] = vsprintf($this->translator->translate($error[0]), $error[1]);
        }

        return $messages;
    }

    public function gatherConfigs($relativeDirectory)
    {
            /** @var array */
        $configs = [];

        if (is_array($relativeDirectory))
        {
            $relativeDirectory = implode(DIRECTORY_SEPARATOR, $relativeDirectory);
        }

        /** @var array */
        $templatePaths = [getcwd() . DIRECTORY_SEPARATOR . $relativeDirectory];

        /** @var int How much from absolute paths should be cut to obtain a relative path. */
        $cwdLength = strlen(getcwd()) + strlen(DIRECTORY_SEPARATOR);

        foreach($templatePaths as $templatePath)
        {
            /** @var \RecursiveDirectoryIterator */
            $directory = new RecursiveDirectoryIterator($templatePath, RecursiveDirectoryIterator::SKIP_DOTS);
            /** @var \RecursiveIteratorIterator */
            $iterator = new RecursiveIteratorIterator($directory);
            /** @var \RegexIterator */
            $regex = new RegexIterator($iterator, '/^(.*?).manifest\.php$/i', RecursiveRegexIterator::MATCH);

            /** Go over all manifests. */
            foreach($regex as $node)
            {

                $config = [];
                $config['real_path'] = $node->getPath();
                $config['real_relative_path'] = str_replace(
                    DIRECTORY_SEPARATOR, '/', substr($config['real_path'], $cwdLength)
                );
                $config['pathname'] = $node->getPathname();
                $config['relative_pathname'] = str_replace(
                    DIRECTORY_SEPARATOR, '/', substr($config['pathname'], $cwdLength)
                );

                $realPath = $node->getRealPath();

                if (!$this->validateFileArray($realPath))
                {
                     $this->error('Error parsing file "%s".', [$config['relative_pathname']]);
                     continue;
                }

                $configFromFile = include $realPath;
                $config = array_merge($config, $configFromFile);

                $configs[] = $config;
            }
        }

        return $configs;
    }

    public function flattenManifestSettings($configs)
    {
        $settings = [];

        foreach($configs as $config)
        {
            /** If "settings_group" is not defined, skip. This config does not add new settings. */
            if (!isset($config['settings_groups']))
            {
                continue;
            }

            /** If "settings_group" is not not an array, prepend error. */
            if (!is_array($config['settings_groups']))
            {
                $this->error(
                    'Key "settings_groups" should be an array, %s found instead.',
                    [gettype($config['settings_groups'])]
                );
                continue;
            }

            foreach($config['settings_groups'] as $settingsGroup)
            {
                /** Prepend error if name isn't set, or isn't a string. */
                if (!isset($settingsGroup['name']) || !is_string($settingsGroup['name']))
                {
                    $this->error(
                        'Manifest at path "%s" has to have "name" key that is a string.',
                        [$config['relative_pathname']]
                    );

                    continue;
                }

                /** Prepend error if prefix isn't set, or isn't a string, or doesn't end with colon. */
                if (
                    !isset($settingsGroup['prefix']) ||
                    !is_string($settingsGroup['prefix']) ||
                    ':' !== substr($settingsGroup['prefix'], -1)
                )
                {
                    $this->error(
                        'Setting "%s" at path "%s" has to have "prefix" key at top level, ' .
                        'that is a string and ends with a colon.',
                        [$settingsGroup['name'], $config['relative_pathname']]
                    );
                    continue;
                }

                foreach($settingsGroup['settings'] as $setting)
                {
                    /** Prepend error is setting does not have a name. */
                    if (!isset($setting['name']) || !is_string($setting['name']) || empty($setting['name']))
                    {
                        $this->error(
                            'Setting group at path "%s" contains unnamed setting, or a name that isn\'t a string.',
                            [$config['relative_pathname']]
                        );

                        continue;
                    }

                    /** Prepend error is label isn't set, or isn't a string. */
                    if (!isset($setting['label']) || !is_string($setting['label']) || empty($setting['label']))
                    {
                        $this->error(
                            'Setting group "%s" at path "%s" contains setting without label, or a label that ' .
                            'isn\'t a string.',
                            [$setting['name'], $config['relative_pathname']]
                        );

                        continue;
                    }

                    $prefix = $settingsGroup['prefix'];
                    $settingsKey = $prefix.$setting['name'];

                    /** Finally, when everything is OK, save setting, optionally using "default_value". */
                    $settings[$settingsKey] = isset($setting['default_value']) ? strval($setting['default_value']) : '';

                    $this->descriptions[$settingsKey] = [
                        'group_name' => $settingsGroup['name'],
                        'group_prefix' => $prefix,
                        'name' => $setting['name'],
                        'label' => $setting['label'],
                    ];
                }
            }
        }

        return $settings;
    }

    /**
     * Returns a key value pair of setting and their values, gathered from application core.
     *
     * @return array
     */
    protected function discoverCoreSettings()
    {
        $configs = $this->gatherConfigs(['module', 'Settings', 'config']);
        $manifestSettings = $this->flattenManifestSettings($configs);
        return $this->pruneExistingSettings($manifestSettings);
    }

    /**
     * Returns a key value pair of setting and their values, gathered from templates directory.
     *
     * @return array
     */
    protected function discoverTemplatesSettings()
    {
        $configs = $this->gatherConfigs('templates');
        $manifestSettings = $this->flattenManifestSettings($configs);
        return $this->pruneExistingSettings($manifestSettings);
    }

    /**
     * Returns a key value pair of setting and their values, gathered from plugins directory.
     *
     * @return array
     */
    protected function discoverPluginsSettings()
    {
        $configs = $this->gatherConfigs('plugins');
        $manifestSettings = $this->flattenManifestSettings($configs);
        return $this->pruneExistingSettings($manifestSettings);
    }

    /**
     * Receives an array an returns a pruned array, skipping those settings names that are already present
     * in the database.
     *
     * @param  array All settings discovered in a given group (core, templates, plugins).
     * @return array Pruned settings, without those keys already present in the database.
     */
    protected function pruneExistingSettings($discoveredSettings)
    {
        foreach($discoveredSettings as $name => $value)
        {
            if (isset($this->flattenSettings[$name]))
            {
                unset($discoveredSettings[$name]);
            }
        }

        return $discoveredSettings;
    }

    /**
     * Add errors to error list.
     */
    protected function error($msg, $keys = [])
    {
        $this->errors[] = [$msg, $keys];
    }

    protected function validateFileArray($realPath)
    {
        $parser = new PhpParser\Parser(new PhpParser\Lexer);

        try
        {
            $parser->parse(file_get_contents($realPath));
            return true;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }
}