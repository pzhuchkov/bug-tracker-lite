<?php
/**
 * @var \App\View\AppView                                            $this
 * @var \App\Model\Entity\Bug[]|\Cake\Collection\CollectionInterface $bugs
 * @var array                                                        $typeList
 * @var bool                                                         $isAuth
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
            <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add'], ['class' => 'nav-link px-2 link-dark']) ?></li>
        </ul>

        <?php if ($isAuth === true): ?>
            <div class="col-md-3 text-end">
                <button type="button"
                        class="btn btn-primary"><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></button>
            </div>
        <?php endif; ?>
    </header>

    <?php
    echo $this->Form->create(
        null,
        [
            'type' => 'get',
            'url'  => $this->request->getRequestTarget(),
        ]
    )
    ?>
    <div class="row g-3  py-3 mb-4">
        <div class="col-auto">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">Filter</span>
                <?php
                echo $this->Form->select(
                    'type',
                    $typeList,
                    [
                        'class'   => 'form-select',
                        'id'      => 'type',
                        'default' => $this->request->getQuery('type'),
                    ]
                );
                ?>
            </div>
        </div>
        <?php
        if ($this->request->getQuery('sort')) {
            echo $this->Form->hidden('sort', ['val' => $this->request->getQuery('sort')]);
        }
        if ($this->request->getQuery('direction')) {
            echo $this->Form->hidden('direction', ['val' => $this->request->getQuery('direction')]);
        }
        ?>
        <div class="col-auto">
            <input id="from-date" type="text" class="form-control" placeholder="From Date" name="from-date"
                   value="<?= $this->request->getQuery('from-date') ?>" autocomplete="off">
        </div>
        <div class="col-auto">
            <input id="to-date" type="text" class="form-control" placeholder="To Date" name="to-date"
                   value="<?= $this->request->getQuery('to-date') ?>" autocomplete="off">
        </div>
        <div class="col-auto">
            <?= $this->Form->button(__('Find'), ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="col-auto">
            <?php
            echo $this->Html->link('Reset', ['action' => 'index'], ['class' => 'btn btn-primary'])
            ?>
        </div>
        <script>
            jQuery('#from-date').datetimepicker({format: 'Y.m.d H:i:s'});
            jQuery('#to-date').datetimepicker({format: 'Y.m.d H:i:s'});
        </script>
    </div>
    <?= $this->Form->end() ?>

    <?php if ($message = $this->Flash->render()): ?>
        <div class="alert alert-warning" role="alert">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <table class="table table-bordered">
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
    <?php if ($this->Paginator->total() > 1): ?>
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
    <?php endif ?>
</div>
