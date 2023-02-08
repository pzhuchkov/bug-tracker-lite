<div class="container">
    <header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
            Login form
        </a>
    </header>
    <?php if ($message = $this->Flash->render()): ?>
        <div class="alert alert-warning" role="alert">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <div class="row justify-content-md-center">
        <div class="col-5">
            <?= $this->Form->create() ?>
            <div class="mb-3">
                <?= $this->Form->control(
                    'email',
                    [
                        'label' => [
                            'class' => 'form-label',
                        ],
                        'class' => 'form-control',
                    ]
                ) ?>
            </div>
            <div class="mb-3">
                <?= $this->Form->control(
                    'password',
                    [
                        'label' => [
                            'class' => 'form-label',
                        ],
                        'class' => 'form-control',
                    ]
                ) ?>
            </div>
            <?= $this->Form->button('Login', ['class' => 'btn btn-primary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
