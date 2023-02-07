<?php
/**
 * @var \App\View\AppView     $this
 * @var \App\Model\Entity\Bug $bug
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Bug'), ['action' => 'edit', $bug->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Bug'), ['action' => 'delete', $bug->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bug->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Bugs'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bug'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="bugs view large-9 medium-8 columns content">
    <h3><?= h($bug->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($bug->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Author') ?></th>
            <td><?= h($bug->author) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Assigned') ?></th>
            <td><?= h($bug->assigned) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($bug->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= $this->BugsData->getTypeById($bug->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->BugsData->getStatusById($bug->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('CreateAt') ?></th>
            <td><?= h($bug->createAt) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('UpdateAt') ?></th>
            <td><?= h($bug->updateAt) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($bug->description) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Comment') ?></h4>
        <?= $this->Text->autoParagraph(h($bug->comment)); ?>
    </div>
</div>
