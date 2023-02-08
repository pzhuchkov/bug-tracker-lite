<?php
/**
 * @var \App\View\AppView     $this
 * @var \App\Model\Entity\Bug $bug
 * @var array                 $typeList
 * @var array                 $statusList
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
    <div class="row justify-content-md-center">
        <div class="col">
            <?= $this->Form->create($bug) ?>
            <fieldset>
                <legend><?= __('Edit Bug') ?></legend>
                <div class="mb-3">
                    <?php
                    echo $this->Form->control('title',
                        [
                            'label' => [
                                'class' => 'form-label',
                            ],
                            'class' => 'form-control',
                        ]);
                    ?>
                </div>
                <div class="mb-3">
                    <?php
                    echo $this->Form->control('assigned_id',
                        [
                            'options' => $users,
                            'label'   => [
                                'class' => 'form-label',
                            ],
                            'class'   => 'form-select',
                        ]
                    );
                    ?>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <?php
                    echo $this->Form->select('type', $typeList,
                        [
                            'class' => 'form-select',
                            'id'    => 'type',
                        ]);
                    ?>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <?php
                    echo $this->Form->select('status', $statusList,
                        [
                            'class' => 'form-select',
                            'id'    => 'status',
                        ]);
                    ?>
                </div>
                <div class="mb-3">
                    <?php
                    echo $this->Form->control('description',
                        [
                            'label' => [
                                'class' => 'form-label',
                            ],
                            'class' => 'form-control',
                        ]);
                    ?>
                </div>
                <div class="mb-3">
                    <?php
                    echo $this->Form->control('comment',
                        [
                            'label' => [
                                'class' => 'form-label',
                            ],
                            'class' => 'form-control',
                        ]);
                    ?>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
