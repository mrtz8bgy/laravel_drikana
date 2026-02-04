<ul class="sidebar-menu">
    <li><a href="{{ route('admin.jewelry.dashboard') }}">داشبورد جواهرات</a></li>

    <li class="treeview">
        <a href="#">مالکان جواهرات</a>
        <ul class="treeview-menu">
            <li><a href="{{ route('admin.jewelry.owners.index') }}">لیست مالکان</a></li>
            <li><a href="{{ route('admin.jewelry.owners.create') }}">افزودن مالک جدید</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">شناسنامه‌ها</a>
        <ul class="treeview-menu">
            <li><a href="{{ route('admin.jewelry.certificates.index') }}">لیست شناسنامه‌ها</a></li>
            <li><a href="{{ route('admin.jewelry.certificates.create') }}">افزودن شناسنامه جدید</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">سفارشات تعمیر</a>
        <ul class="treeview-menu">
            <li><a href="{{ route('admin.jewelry.repair-orders.index') }}">لیست سفارشات تعمیر</a></li>
            <li><a href="{{ route('admin.jewelry.repair-orders.create') }}">ثبت سفارش تعمیر</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">انتقال جواهرات</a>
        <ul class="treeview-menu">
            <li><a href="{{ route('admin.jewelry.transfers.create') }}">ایجاد انتقال</a></li>
        </ul>
    </li>

    <li><a href="{{ route('admin.jewelry.reports.certificates') }}">گزارش شناسنامه‌ها</a></li>
</ul>
