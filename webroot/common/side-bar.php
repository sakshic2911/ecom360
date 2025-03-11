<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="side-bar">
    <ul class="nav navbar-nav side-nav scrollbar bg-hunterBlue" id="style-3">
        <li class="hide">
            <div class="user-profile">
                <img src="img/user.jpg">
                <span>Super Admin</span>
            </div>
        </li>
        <li class="active">
            <a href="dashboard.php"><i class="fa fa-dashboard"></i><span>Dashboard </span></a>
        </li>
        <li>
            <a href="createcompany.php"><i class="fa fa-industry"></i><span>Setup </span></a>
        </li>
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#input"><i class="fa fa-dashboard"></i> <span>Input</span> <i class="fa fa-fw fa-caret-down pull-right"></i></a>
            <ul id="input" class="collapse submenu">
                <li><a href="#">Sales</a></li>
                <li><a href="javascript:void(0);" data-toggle="collapse" data-target="#inventory"><span>Inventory</span><i class="fa fa-fw fa-caret-down pull-right"></i></a>
                    <ul id="inventory" class="collapse subSubMenu">
                        <li><a href="#">System</a></li>
                        <li><a href="#">Physical</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#forecast"><i class="fa fa-dashboard"></i> <span>Forecast</span> <i class="fa fa-fw fa-caret-down pull-right"></i></a>
            <ul id="forecast" class="collapse submenu">
                <li><a href="inventoryforecast.php">Inventory</a></li>
                <li><a href="emergencyforecast.php">Emergency</a></li>
            </ul>
        </li>
        <li>
            <a href="#"><i class="fa fa-user"></i><span>Reports</span></a>
        </li>
        <li>
            <a href="#"><i class="fa fa-users"></i><span>Forecast Parameters</span></a>
        </li>
        <li>
            <a href="#"><i class="fa fa-pencil-square-o"></i><span>Forecast</span></a>
        </li>
        <li>
            <a href="#"><i class="fa fa-pencil-square-o"></i><span>User Mngt.</span></a>
        </li>
        <li>
            <a href="javascript:void(0);" data-toggle="collapse" data-target="#widget"><i class="fa fa-dashboard"></i> <span>Widget</span> <i class="fa fa-fw fa-caret-down pull-right"></i></a>
            <ul id="widget" class="collapse submenu">
                <li><a href="form.php">form</a></li>
                <li><a href="charts.php">charts</a></li>
                <li><a href="datatables.php">datatable</a></li>
            </ul>
        </li>
    </ul>
</div>