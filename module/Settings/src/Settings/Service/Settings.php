<?php
/**
 * Settings trait.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Settings\Service;

trait Settings
{
    public function onDispatchSettings($e)
    {
        /** @var \Settings\Entity\Setting[] */
        $settingsCollection = $this->getEntityManager()->getRepository('Settings\Entity\Setting')->findAll();

        /** @var array */
        $settings = $this->unflattenSettingsCollection($settingsCollection);

        /** Inject settings into service locator. */
        $e
            ->getApplication()
            ->getServiceManager()
            ->setAllowOverride(true)
            ->setService('Settings', $settings)
            ->setAllowOverride(false);
    }

    /**
     * Unflattens settings collection.
     *
     * @example
     * Every key like 'one:two:three' becomes an array:
     * [
     *     'one' => [
     *         'two' => [
     *             'three' => [],
     *         ],
     *     ],
     * ]
     */
    public function unflattenSettingsCollection($settingsCollection)
    {
        /** @var array */
        $settings = [];

        foreach($settingsCollection as $setting)
        {
            $keyParts = array_reverse(explode(':', $setting->name));

            $level = &$settings;

            foreach($keyParts as $index => $part)
            {
                $settings = $this->unflattenSettingsCollectionRecursive($settings, $keyParts, $setting->value);
            }
        }

        return $settings;
    }

    protected function unflattenSettingsCollectionRecursive($settings, $keyParts, $value)
    {
        $key = end($keyParts);

        $settings[$key] = isset($settings[$key]) ? $settings[$key] : [];

        if (1 === count($keyParts))
        {
            $settings[$key] = $value;
        }
        else
        {
            array_pop($keyParts);
            $settings[$key] = $this->unflattenSettingsCollectionRecursive($settings[$key], $keyParts, $value);
        }

        return $settings;
    }
}