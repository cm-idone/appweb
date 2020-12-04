<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Locations/index.min.js']);

?>

%{header}%
<header class="modbar">
    <div class="title">
        <span><i class="fas fa-truck"></i><strong>{$lang.locations}</strong></span>
    </div>
    <div class="buttons">
        <?php if (Permissions::user(['create_locations']) == true) : ?>
            <a data-action="create_location" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
        <?php endif; ?>
        <fieldset class="fields-group big">
            <div class="compound st-4-left">
                <span><i class="fas fa-search"></i></span>
                <input type="text" data-search="locations" placeholder="{$lang.search}">
            </div>
        </fieldset>
    </div>
</header>
<main>
    <div class="tbl-st-2" data-table="locations">
        <?php foreach ($data['locations'] as $value) : ?>
            <div>
                <figure>
                    <img src="{$path.images}location.png">
                </figure>
                <h4><?php echo $value['name']; ?></h4>
                <div class="button">
                    <?php if (Permissions::user(['block_locations','unblock_locations']) == true) : ?>
                        <?php if ($value['blocked'] == true) : ?>
                            <a data-action="unblock_location" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.unblock}</span></a>
                        <?php elseif ($value['blocked'] == false) : ?>
                            <a data-action="block_location" data-id="<?php echo $value['id']; ?>"><i class="fas fa-unlock"></i><span>{$lang.block}</span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (Permissions::user(['delete_locations']) == true) : ?>
                        <?php if ($value['blocked'] == false) : ?>
                            <a data-action="delete_location" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (Permissions::user(['update_locations']) == true) : ?>
                        <?php if ($value['blocked'] == false) : ?>
                            <a data-action="update_location" data-id="<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?php if (Permissions::user(['create_locations','update_locations']) == true) : ?>
    <section class="modal" data-modal="create_location">
        <div class="content">
            <main>
                <form>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="text" name="name">
                        </div>
                        <div class="title">
                            <h6>{$lang.name}</h6>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="button">
                            <a class="alert" button-close><i class="fas fa-times"></i></a>
                            <button type="submit" class="success"><i class="fas fa-plus"></i></button>
                        </div>
                    </fieldset>
                </form>
            </main>
        </div>
    </section>
<?php endif; ?>
<?php if (Permissions::user(['delete_locations']) == true) : ?>
    <section class="modal alert" data-modal="delete_location">
        <div class="content">
            <main>
                <i class="fas fa-trash"></i>
                <div>
                    <a button-close><i class="fas fa-times"></i></a>
                    <a button-success><i class="fas fa-check"></i></a>
                </div>
            </main>
        </div>
    </section>
<?php endif; ?>
