<?php

namespace Drupal\Tests\conflict\Kernel;

use Drupal;
use Drupal\entity_test\Entity\EntityTestRev;
use Drupal\conflict;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * @group conflict
 */
class KernelLcaTest extends EntityKernelTestBase {

    protected $entityType;
    /**
     * Modules to enable.
     *
     * @var array
     */
    public static $modules = ['entity_test', 'conflict', 'system', 'user'];

    protected function setUp()
    {
        // First setup the needed entity types before installing the views.
        parent::setUp();
        $this->installEntitySchema('entity_test');
        $this->installEntitySchema('entity_test_rev');
    }

        public function testsimple() {
            $entity = EntityTestRev::create(['name' => 'revision 1']);
            $entity->save();
            $entity->setName('revision 2');
            $entity->setNewRevision();
            $entity->save();
            $entity->setName('revision 3');
            $entity->setNewRevision();
            $entity->save();
            $entity->setName('revision 4');
            $entity->setNewRevision();
            $entity->save();
            $revision2 = Drupal::entityTypeManager()
                ->getStorage('entity_test_rev')
                ->loadRevision(2);
            $revision3 = Drupal::entityTypeManager()
                ->getStorage('entity_test_rev')
                ->loadRevision(3);
            $revision4 = Drupal::entityTypeManager()
                ->getStorage('entity_test_rev')
                ->loadRevision(4);

            $manager = Drupal::service('conflict.conflict_manager');
            $revisionid1 = $manager->resolveLowestCommonAncestor($revision2, $revision3);
            $revisionLca = Drupal::entityTypeManager()
                ->getStorage('entity_test_rev')
                ->loadRevision($revisionid1);
            $this->assertEquals($revisionLca->label(), "revision 1");
            $revisionid2 = $manager->resolveLowestCommonAncestor($revision3, $revision4);
            $revisionLca = Drupal::entityTypeManager()
                ->getStorage('entity_test_rev')
                ->loadRevision($revisionid2);
            $this->assertEquals($revisionLca->label(), "revision 2");
    }
}
