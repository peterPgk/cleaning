<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
    {{--<div class="user-panel">--}}
    {{--<div class="pull-left image">--}}

    {{--<img src="img/user2-160x160.jpg" class="img-circle" alt="User Image">--}}
    {{--</div>--}}
    {{--<div class="pull-left info">--}}
    {{--<p>Alexander Pierce</p>--}}
    {{--<!-- Status -->--}}
    {{--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
    {{--</div>--}}
    {{--</div>--}}

    <!-- search form (Optional) -->
    {{--<form action="#" method="get" class="sidebar-form">--}}
    {{--<div class="input-group">--}}
    {{--<input type="text" name="q" class="form-control" placeholder="Search...">--}}
    {{--<span class="input-group-btn">--}}
    {{--<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>--}}
    {{--</button>--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--</form>--}}
    <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">ADMIN MENU</li>
            <li><a href="/admin"><i class="fa fa-group"></i> <span>Dashboard</span></a></li>
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Company</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/admin/company/general">General Details</a></li>
                    <li><a href="/admin/company/additional">Additional Details</a></li>
                    <li><a href="/admin/company/password">Change password</a></li>
                    <li><a href="/admin/user"><i class="fa fa-group"></i> <span>Users</span></a></li>
                    {{--<li><a href="/admin/company/rating">Rating</a></li>--}}
                </ul>
            </li>

            {{--<li class="treeview">--}}
            {{--<a href="#"><i class="fa fa-group"></i> <span>Users</span>--}}
            {{--<span class="pull-right-container">--}}
            {{--<i class="fa fa-angle-left pull-right"></i>--}}
            {{--</span>--}}
            {{--</a>--}}
            {{--<ul class="treeview-menu">--}}
            {{--<li><a href="/admin/user"><i class="fa fa-group"></i> <span>All Users</span></a></li>--}}
            {{--<li><a href="/admin/user/new"><i class="fa fa-user"></i> <span> New User</span></a></li>--}}
            {{--</ul>--}}
            {{--</li>--}}

            <li><a href="/admin/company/rating"><i class="fa fa-group"></i> <span>Reviews</span></a></li>

            <li><a href="/admin/schedule"><i class="fa fa-calendar-plus-o"></i> <span> Availability</span></a></li>

            <li><a href="/admin/services"><i class="fa fa-sign-language"></i> <span> Prices</span></a></li>

            <li><a href="/admin/orders"><i class="fa fa-shopping-cart"></i> <span> Reservations</span></a></li>

            <li><a href="/admin/postcodes"><i class="fa fa-user"></i> <span> Postcodes</span></a></li>

            <li><a href="/admin/subscription"><i class="fa fa-user"></i> <span> Subscription</span></a></li>

            <li><a href="/admin/help"><i class="fa fa-user"></i> <span> Help</span></a></li>

            <!-- Optionally, you can add icons to the links -->
            {{--<li><a href="#"><i class="fa fa-link"></i> <span>Users</span></a></li>--}}
            {{--<li><a href="#"><i class="fa fa-link"></i> <span>Schedule</span></a></li>--}}
            {{--<li class="treeview">--}}
            {{--<a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>--}}
            {{--<span class="pull-right-container">--}}
            {{--<i class="fa fa-angle-left pull-right"></i>--}}
            {{--</span>--}}
            {{--</a>--}}
            {{--<ul class="treeview-menu">--}}
            {{--<li><a href="#">Link in level 2</a></li>--}}
            {{--<li><a href="#">Link in level 2</a></li>--}}
            {{--</ul>--}}
            {{--</li>--}}
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>