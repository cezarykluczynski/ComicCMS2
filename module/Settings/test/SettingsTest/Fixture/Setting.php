<?php
/**
 * Fixtures for single setting entity.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace SettingsTest\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use ComicCmsTestHelper\Fixture\FixtureRepository;
use Settings\Entity\Setting as SettingEntity;
use Zend\Math\Rand;

class Setting extends FixtureRepository
{
    protected $entityClass = 'Settings\Entity\Setting';

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $setting = new SettingEntity();
        $this->entities[] = $setting;
        $setting->name = 'test_entry_'.Rand::getInteger(1000, 100000);
        $setting->value = 'test_setting';
        $this->manager->persist($setting);
        $this->manager->flush();
    }
}