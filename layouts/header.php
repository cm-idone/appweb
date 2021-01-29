<?php defined('_EXEC') or die; ?>

<header class="topbar">
    <?php if (!empty(Session::get_value('vkye_account'))) : ?>
        <div class="account">
            <figure>
                <img src="<?php echo (!empty(Session::get_value('vkye_account')['avatar']) ? '{$path.uploads}' . Session::get_value('vkye_account')['avatar'] : 'https://cdn.codemonkey.com.mx/monkeyboard/assets/images/account.png'); ?>">
            </figure>
            <div>
                <h4 class="<?php echo ((Session::get_value('vkye_account')['status'] == true) ? 'online' : 'offline'); ?>"><i class="fas fa-circle"></i><?php echo Session::get_value('vkye_account')['name']; ?></h4>
                <span>{$lang.<?php echo Session::get_value('vkye_account')['type']; ?>}</span>
            </div>
        </div>
    <?php endif; ?>
    <nav>
        <ul>
            <li><a data-action="open_rightbar"><i class="fas fa-bars"></i></a></li>
        </ul>
    </nav>
    <div class="user">
        <div>
            <h4><?php echo Session::get_value('vkye_user')['firstname'] . ' ' . Session::get_value('vkye_user')['lastname']; ?><i class="fas fa-circle"></i></h4>
            <span><?php echo Session::get_value('vkye_user')['email']; ?></span>
        </div>
        <figure>
            <img src="<?php echo (!empty(Session::get_value('vkye_user')['avatar']) ? '{$path.uploads}' . Session::get_value('vkye_user')['avatar'] : 'https://cdn.codemonkey.com.mx/monkeyboard/assets/images/user.png'); ?>">
        </figure>
    </div>
    <nav>
        <ul>
            <li><a><i class="fas fa-star"></i></a></li>
            <li><a><i class="fas fa-heart"></i></a></li>
            <li><a><i class="fas fa-meteor"></i></a></li>
        </ul>
    </nav>
</header>
<header class="leftbar">
    <nav>
        <ul>
            <li><a href="/about" class="logotype"><img src="{$path.images}imagotype_white.png"><span><?php echo Configuration::$web_page . ' ' . Configuration::$web_page_version; ?> by Code Monkey</span></a></li>
        </ul>
        <ul>
            <li><a href="/search"><i class="fas fa-search"></i><span>{$lang.search}</span></a></li>
            <li><a href="/dashboard"><i class="fas fa-igloo"></i><span>{$lang.dashboard}</span></a></li>
        </ul>
        <?php if (!empty(Session::get_value('vkye_account'))) : ?>
            <?php if (Permissions::account(['laboratory']) == true AND Permissions::user(['laboratory','alcoholic','antidoping','covid'], true) == true) : ?>
                <ul>
                    <?php if (Permissions::user(['laboratory'], true) == true) : ?>
                        <li><a href="/laboratory/marbu"><img src="{$path.images}marbu_logotype_color.png"><span>Marbu {$lang.laboratory}</span></a></li>
                    <?php endif; ?>
                    <?php if (Permissions::user(['alcoholic'], true) == true) : ?>
                        <li><a href="/laboratory/alcoholic"><i class="fas fa-cocktail"></i><span>{$lang.alcoholic}</span></a></li>
                    <?php endif; ?>
                    <?php if (Permissions::user(['antidoping'], true) == true) : ?>
                        <li><a href="/laboratory/antidoping"><i class="fas fa-cannabis"></i><span>{$lang.antidoping}</span></a></li>
                    <?php endif; ?>
                    <?php if (Permissions::user(['covid'], true) == true) : ?>
                        <li><a href="/laboratory/covid"><i class="fas fa-virus"></i><span>{$lang.covid}</span></a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
            <?php if (Permissions::account(['laboratory']) == true AND Permissions::user(['employees'], true) == true) : ?>
                <ul>
                    <li><a href="/employees"><i class="fas fa-user-friends"></i><span>{$lang.employees}</span></a></li>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
</header>
<header class="rightbar">
    <div>
        <div class="accounts">
            <?php if (!empty(Session::get_value('vkye_user')['accounts'])) : ?>
                <?php foreach (Session::get_value('vkye_user')['accounts'] as $key => $value) : ?>
                    <div class="item">
                        <figure>
                            <img src="<?php echo (!empty($value['avatar']) ? '{$path.uploads}' . $value['avatar'] : 'https://cdn.codemonkey.com.mx/monkeyboard/assets/images/account.png'); ?>">
                        </figure>
                        <div>
                            <h4><?php echo $value['name']; ?></h4>
                            <?php if ($value['status'] == true) : ?>
                                <?php if (Session::get_value('vkye_account')['id'] == $value['id']) : ?>
                                    <span class="online"><i class="fas fa-circle"></i>{$lang.online}</span>
                                <?php else : ?>
                                    <span class="onhold"><i class="fas fa-circle"></i>{$lang.onhold}</span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span class="offline"><i class="fas fa-circle"></i>{$lang.offline}</span>
                            <?php endif; ?>
                        </div>
                        <a data-action="switch_account" data-id="<?php echo $value['id']; ?>"></a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="empty">
                    <i class="far fa-sad-tear"></i>
                    <p>{$lang.not_accounts}</p>
                </div>
            <?php endif; ?>
        </div>
        <nav>
            <ul>
                <li><a href="/dashboard"><i class="fas fa-igloo"></i>{$lang.dashboard}</a></li>
            </ul>
            <?php if (!empty(Session::get_value('vkye_account'))) : ?>
                <?php if (Permissions::account(['laboratory']) == true AND Permissions::user(['laboratory','alcoholic','antidoping','covid'], true) == true) : ?>
                    <ul>
                        <li><h4>{$lang.laboratory}</h4></li>
                        <?php if (Permissions::user(['laboratory'], true) == true) : ?>
                            <li><a href="/laboratory/marbu"><figure><img src="{$path.images}marbu_logotype_color.png"></figure>Marbu {$lang.laboratory}</a></li>
                        <?php endif; ?>
                        <?php if (Permissions::user(['alcoholic'], true) == true) : ?>
                            <li><a href="/laboratory/alcoholic"><i class="fas fa-cocktail"></i>{$lang.alcoholic}</a></li>
                        <?php endif; ?>
                        <?php if (Permissions::user(['antidoping'], true) == true) : ?>
                            <li><a href="/laboratory/antidoping"><i class="fas fa-cannabis"></i>{$lang.antidoping}</a></li>
                        <?php endif; ?>
                        <?php if (Permissions::user(['covid'], true) == true) : ?>
                            <li><a href="/laboratory/covid"><i class="fas fa-virus"></i>{$lang.covid}</a></li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
                <?php if (Permissions::account(['laboratory']) == true AND Permissions::user(['employees','locations'], true) == true) : ?>
                    <ul>
                        <li><h4>{$lang.administration}</h4></li>
                        <?php if (Permissions::user(['employees'], true) == true) : ?>
                            <li><a href="/employees"><i class="fas fa-user-friends"></i>{$lang.employees}</a></li>
                        <?php endif; ?>
                        <?php if (Permissions::user(['locations'], true) == true) : ?>
                            <li><a href="/locations"><i class="fas fa-map-marker-alt"></i>{$lang.locations}</a></li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
                <?php if (Permissions::user(['account'], true) == true) : ?>
                    <ul>
                        <li><h4>{$lang.my_account}</h4></li>
                        <?php if (Permissions::user(['update_account'])) : ?>
                            <li><a><i class="fas fa-robot"></i>{$lang.profile}</a></li>
                            <li><a><i class="fas fa-users"></i>{$lang.work_team}</a></li>
                            <li><a><i class="fas fa-cog"></i>{$lang.settings}</a></li>
                        <?php endif; ?>
                        <?php if (Permissions::user(['payment_account'])) : ?>
                            <li><a><i class="fas fa-ghost"></i>{$lang.payment_center}</a></li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
            <ul>
                <li><h4>{$lang.my_user}</h4></li>
                <li><a><i class="fas fa-user-astronaut"></i>{$lang.profile}</a></li>
                <li><a><i class="fas fa-rocket"></i>{$lang.accounts}</a></li>
            </ul>
            <ul>
                <li><a>{$lang.about}</a></li>
                <li><a>{$lang.copyright}</a></li>
                <li><a>{$lang.terms_and_conditions}</a></li>
                <li><a>{$lang.privacy_policies}</a></li>
            </ul>
            <ul>
                <li><a href="/about"><?php echo Configuration::$web_page . ' ' . Configuration::$web_page_version; ?> by Code Monkey</a></li>
                <li><a href="" target="_blank">Power by Valkyrie</a></li>
            </ul>
            <ul>
                <li><a class="reverse">{$lang.technical_support}<i class="fas fa-headset"></i></a></li>
                <li><a class="reverse" data-action="logout">{$lang.logout}<i class="fas fa-power-off"></i></a></li>
            </ul>
        </nav>
    </div>
</header>
