<?php
/**
 * @var \App\View\AppView     $this
 * @var \App\Model\Entity\Bug $bug
 * @var array                 $typeList
 * @var array                 $statusList
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $bug->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $bug->id)]
            )
            ?></li>
        <li><?= $this->Html->link(__('List Bugs'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="bugs form large-9 medium-8 columns content">
    <?= $this->Form->create($bug) ?>
    <fieldset>
        <legend><?= __('Edit Bug') ?></legend>
        <?php
        echo $this->Form->control('title');
        echo $this->Form->select('type', $typeList);
        echo $this->Form->select('status', $statusList);
        echo $this->Form->control('description');
        echo $this->Form->control('comment');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
