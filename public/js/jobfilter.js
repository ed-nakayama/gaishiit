document.addEventListener('DOMContentLoaded', function() {
    const containers = document.querySelectorAll('.container');

    containers.forEach(container => {
        const menuItems = container.querySelectorAll('.menu-item');
        const contents = container.querySelectorAll('.content');

        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                const areaId = this.getAttribute('data-area');
                const contentToShow = container.querySelector(`#${areaId}`);

                // 既に表示されている場合は非表示にする
                if (contentToShow.classList.contains('active')) {
                    contentToShow.classList.remove('active');
                    contentToShow.style.display = 'none';
                    this.classList.remove('active'); // メニューの背景色を元に戻す
                    return;
                }

                contents.forEach(content => {
                    content.classList.remove('active');
                    content.style.display = 'none';
                });
                menuItems.forEach(item => {
                    item.classList.remove('active'); // 他のメニューの背景色を元に戻す
                });

                contentToShow.classList.add('active');
                this.classList.add('active'); // メニューの背景色をグレーに

                // PC表示の場合
                if (window.innerWidth >= 768) {
                    contentToShow.style.display = 'block';
                } else {
                    // スマホ表示の場合
                    const menu = this.parentNode;
                    menu.insertBefore(contentToShow, this.nextSibling);
                    contentToShow.style.display = 'block';
                }
            });
        });

        // 初期表示設定
        if (window.innerWidth >= 768) {
            const firstContent = container.querySelector('.content');
            const firstMenuItem = container.querySelector('.menu-item[data-area="' + firstContent.id + '"]');
            firstContent.classList.add('active');
            firstContent.style.display = 'block';
            firstMenuItem.classList.add('active'); // 初期表示時に最初のメニューをアクティブに
        }
    });
});
