            <div class="news-comments two-blocks">
                <div class="news left-block">
                    <h2>Что нового?</h2>
                    <div class="column">
                        <p><em class="news-about">у компании</em></p>
                        <? if (isset($widgets['company_news']['items'])) :?>
                        <? foreach ($widgets['company_news']['items'] as $service) :?>
                            <div class="news-comment-item">
                                <span class="date-title"><a href="<?=$service['item']->link;?>" target="<?=$service['item']->link_new_window ? '_blank' : '_self';?>"><?=$service['item']->title;?></a></span>
                                <?=$service['item']->description;?></p>(<a class="more" href="<?=$service['item']->link;?>" target="<?=$service['item']->link_new_window ? '_blank' : '_self';?>">подробнее</a>)
                            </div>
                        <? endforeach; ?>
                        <? endif; ?>
                    </div>
                    <div class="column right-block">
                        <p><em class="news-about">среди проектов</em></p>
                        <? if (isset($widgets['company_projects']['items'])) :?>
                        <? foreach ($widgets['company_projects']['items'] as $service) :?>
                            <div class="news-comment-item">
                                <span class="date-title"><a href="<?=$service['item']->link;?>" target="<?=$service['item']->link_new_window ? '_blank' : '_self';?>"><?=$service['item']->title;?></a></span>
                                <?=$service['item']->description;?></p>(<a class="more" href="<?=$service['item']->link;?>" target="<?=$service['item']->link_new_window ? '_blank' : '_self';?>">подробнее</a>)
                            </div>
                        <? endforeach; ?>
                        <? endif; ?>
                    </div>
                </div>
                <div class="comments right-block">
                    <h2>комментарии</h2>
                    <div class="column">
                        <p><em class="news-about">по проектам</em></p>
                        <? if (isset($widgets['comments']['items'])) :?>
                        <? foreach ($widgets['comments']['items'] as $service) :?>
                            <div class="news-comment-item">
                                <span class="date-title"><a href="<?=$service['item']->link;?>" target="<?=$service['item']->link_new_window ? '_blank' : '_self';?>"><?=$service['item']->title;?></a></span>
                                <?=$service['item']->description;?>
                            </div>
                        <? endforeach; ?>
                        <? endif; ?>
                    </div>
                </div>
            </div>
            <div class="links-block">
                <div class="marker"></div>
                <h2>Cразу к цели</h2>
                <div class="two-blocks">
                    <div class="column">
                        <ul>
                            <li><a href="/">Главная страница</a></li>
                            <li><a href="/about">О компании</a></li>
                            <li><a href="/job">Вакансии</a></li>
                            <li><a href="/reviews">Отзывы</a></li>
                            <li><a href="/docs">Типовые документы</a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <ul>
                            <li><a href="/project">Проекты</a></li>
                            <li><a href="/service">Мы выполняем</a></li>
                            <li><a href="/projecting">По проектированию</a></li>
                            <li><a href="/autor_control">По авторскому надзору</a></li>
                            <li><a href="/monitoring">По мониторингу</a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <ul>
                            <li><a href="/bld_control">По строительному контролю</a></li> 
                            <li><a href="/er_work">По монтажным работам</a></li>
                            <li><a href="/envr_science">По охране окружающей среды</a></li>
                            <li><a href="/alarm_system">По авторской системе безопасности</a></li>
                            <li><a href="/tenders">Закупки</a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <ul>
                            <li><a href="/all_news">Новости</a></li>
                            <li><a href="/nws_project">По проектам </a></li>
                            <li><a href="/nws_about">О компании</a></li>
                            <li><a href="/articles">Статьи</a></li>
                            <li><a href="/partners">Партнерам</a></li>
                        </ul>
                    </div>
                    <div class="column last">
                        <ul>
                            <li><a href="/contacts">Как нас найти?</a></li>
                            <li><a href="/fos">Написать нам</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="spacer"></div>
        <footer class="footer">
            <div class="marker"></div>
            <p class="low"><a href="#">Юридические правовые аспекты</a></p>
            <p class="copy">All Right Reserved &copy;<br> United Technolodgy Compаny</p>
            <p class="contacts">Наш адрес:<br>
            112345, Москва,<br>
            Кутузовский проспект<br>
            д. 36, стр. 23<br/>
            тел: +7 495 600 45 80<br/>
            e-mail: <a href="mailto:info@utc-russia.ru">info@utc-russia.ru</a><br/>
            </p>
            <ul class="icons-footer">
                <li><a href="/search" title="Поиск"><img src="/img/site/icons/search.png" alt="Поиск"></a></li>
                <li><a href="/all_news" title="Лента новостей"><img src="/img/site/icons/list.png" alt="Лента новостей"></a></li>
                <li><a href="/docs" title="Типовые документы"><img src="/img/site/icons/registration.png" alt="Типовые документы"></a></li>
                <li><a href="tel:%2b74956004580" title="Быстрый звонок для мобильных устройств"><img src="/img/site/icons/phone.png" alt="Быстрый звонок для мобильных устройств"></a></li>
                <li><a href="/fos" title="Отправить письмо"><img src="/img/site/icons/mail.png" alt="Отправить письмо"></a></li>
                <li><a href="https://maps.google.com/maps?q=Москва,+Кутузовский+проспект+д.+36,+стр.+23&hl=ru&ie=UTF8&sll=37.0625,-95.677068&sspn=40.052282,86.572266&hnear=просп.+Кутузовский,+36+строение+23,+Москва,+Россия&t=m&z=16" title="Ссылка на  место на карте" target="_blank"><img src="/img/site/icons/link.png" alt="Ссылка на  место на карте"></a></li>
                <li><a href="#" title="fb"><img src="/img/site/icons/fb.png" alt="fb"></a></li>
                <li><a href="#" title="twitter"><img src="/img/site/icons/twitter.png" alt="twitter"></a></li>
                <li><a href="#" title="vk"><img src="/img/site/icons/vk.png" alt="vk"></a></li>
            </ul>
        </footer>
    </div>
</body>
</html>
