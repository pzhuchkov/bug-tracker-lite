<?php
/**
 * @var \App\View\AppView     $this
 * @var \App\Model\Entity\Bug $bug
 */
?>

<div class="container">
    <header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
            <?= $this->fetch('title') ?>
        </a>
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><?= $this->Html->link(__('Bugs'), ['controller' => 'Bugs', 'action' => 'index'], ['class' => 'nav-link px-2 link-dark']) ?></li>
            <li><?= $this->Html->link(__('New Bug'), ['controller' => 'Bugs', 'action' => 'add'], ['class' => 'nav-link px-2 link-dark']) ?></li>
            <li><?= $this->Html->link(__('Users'), ['controller' => 'Users', 'action' => 'index'], ['class' => 'nav-link px-2 link-dark']) ?></li>
            <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add'], ['class' => 'nav-link px-2 link-dark']) ?></li>
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

    <h1 class="display-3"><?= h($bug->title) ?></h1>
    <table class="table ">
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
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h(date_format($bug->created, 'Y-m-d H:i:s')) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h(date_format($bug->modified, 'Y-m-d H:i:s')) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($bug->description) ?></td>
        </tr>
    </table>
    <div class="row">
        <div class="bd-example">
            <h4><?= __('Comment') ?></h4>
            <?= $this->Text->autoParagraph(h($bug->comment)); ?>
        </div>
    </div>
</div>
