<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav class="d-inline-block" aria-label="<?= lang('Pager.pageNavigation') ?>">
	<ul class="pagination mb-0">
		<?php if ($pager->hasPrevious()) : ?>
			<li class="page-item">
				<a class="page-link" href="<?= str_replace("index.php/","",$pager->getFirst()); ?>" aria-label="<?= lang('Pager.first') ?>" data-ci-pagination-page="1">
					<span aria-hidden="true"><?= lang('Pager.first') ?></span>
				</a>
			</li>
			<li class="page-item">
				<a class="page-link" href="<?= str_replace("index.php/","",$pager->getPrevious()); ?>" aria-label="<?= lang('Pager.previous') ?>" data-ci-pagination-page="<?= substr(strstr($pager->getPrevious(), 'page='), strlen('page=')); ?>">
					<span aria-hidden="true"><?= lang('Pager.previous') ?></span>
				</a>
			</li>
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<li class="page-item <?php if($link['active'] == 'active'){ echo 'active';} ?>">
				<a class="page-link" href="<?= str_replace("index.php/","",$link['uri']); ?>" data-ci-pagination-page="<?= $link['title'] ?>">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<li class="page-item">
				<a class="page-link" href="<?= str_replace("index.php/","",$pager->getNext()); ?>" aria-label="<?= lang('Pager.next') ?>" data-ci-pagination-page="<?= substr(strstr($pager->getNext(), 'page='), strlen('page=')); ?>">
					<span aria-hidden="true"><?= lang('Pager.next') ?></span>
				</a>
			</li>
			<li class="page-item">
				<a class="page-link" href="<?= str_replace("index.php/","",$pager->getLast()); ?>" aria-label="<?= lang('Pager.last') ?>" data-ci-pagination-page="<?= substr(strstr($pager->getLast(), 'page='), strlen('page=')); ?>">
					<span aria-hidden="true"><?= lang('Pager.last') ?></span>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>
