<?php
/**
 * @var \App\View\AppView                                            $this
 * @var \App\Model\Entity\Bug[]|\Cake\Collection\CollectionInterface $bugs
 */
?>

<div class="container">
    <header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
            <?= $this->fetch('title') ?>
        </a>
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><?= $this->Html->link(__('Bugs'), ['controller' => 'Bugs', 'action' => 'index'], ['class' => 'nav-link px-2 link-secondary']) ?></li>
            <li><?= $this->Html->link(__('New Bug'), ['controller' => 'Bugs', 'action' => 'add'], ['class' => 'nav-link px-2 link-dark']) ?></li>
            <li><?= $this->Html->link(__('Users'), ['controller' => 'Users', 'action' => 'index'], ['class' => 'nav-link px-2 link-dark']) ?></li>
        </ul>

        <div class="col-md-3 text-end">
            <button type="button"
                    class="btn btn-primary"><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></button>
        </div>
    </header>
    <?php if ($message = $this->Flash->render()): ?>
        <div class="alert alert-warning" role="alert">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col"><?= $this->Paginator->sort('id') ?></th>
            <th scope="col"><?= $this->Paginator->sort('title') ?></th>
            <th scope="col"><?= $this->Paginator->sort('type') ?></th>
            <th scope="col"><?= $this->Paginator->sort('status') ?></th>
            <th scope="col"><?= $this->Paginator->sort('author') ?></th>
            <th scope="col"><?= $this->Paginator->sort('assigned') ?></th>
            <th scope="col"><?= $this->Paginator->sort('createAt') ?></th>
            <th scope="col"><?= $this->Paginator->sort('updateAt') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($bugs as $bug): ?>
            <tr>
                <td><?= $this->Html->link($bug->id, ['action' => 'view', $bug->id]) ?></td>
                <td><?= $this->Html->link($bug->title, ['action' => 'view', $bug->id]) ?></td>
                <td><?= \App\Model\Entity\Bug::getTypeList()[$bug->type] ?></td>
                <td><?= \App\Model\Entity\Bug::getStatusList()[$bug->status] ?></td>
                <td><?= h($bug->author) ?></td>
                <td><?= h($bug->assigned) ?></td>
                <td><?= h(date_format($bug->createAt, 'Y-m-d H:i:s')) ?></td>
                <td><?= h($bug->updateAt ? date_format($bug->updateAt, 'Y-m-d H:i:s') : '') ?></td>
                <td class="actions">
                    <?= $this->Html->link('Edit', ['action' => 'edit', $bug->id, 'escapeTitle' => true]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $bug->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bug->id)]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item"><?= $this->Paginator->prev('<<') ?></li>
                <?= $this->Paginator->numbers() ?>
                <li class="page-item"><?= $this->Paginator->next('>>') ?></li>
            </ul>
        </nav>

        <div class="alert alert-light" role="alert">
            <?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?>
        </div>
    </div>
</div>
