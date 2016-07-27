<?php

namespace Drupal\conflict;

use Drupal\Core\Entity\RevisionableInterface;

class SimpleMergeResolver implements ConflictResolverInterface
{
  public function applies()
  {
    return True;
  }

  /**
   * Returns the last created revision Id.
   *
   * @param RevisionableInterface $revision1
   * @param RevisionableInterface $revision2
   * @param RevisionableInterface $revision3
   *
   * @return mixed 
   */
  public function merge(RevisionableInterface $revision1, RevisionableInterface $revision2, RevisionableInterface $revision3)
  {
    $revid1 = $revision1->getRevisionId();
    $revid2 = $revision2->getRevisionId();
    $revid3 = $revision3->getRevisionId();
    return max($revid1, $revid2, $revid3);
  }
}
