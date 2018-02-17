<?php
//
$active = 'active';
$selected = 'selectedmenu';
$userType = $this->request->session()->read('Auth.User.user_type');
?>
<style>
    .navbar-logout {
        height: 60px;
        font-size: 16px;
        line-height: 50px;
        padding-left: 112px;
    }
    .dropdown-menu {
        top: 99% !important;
    }
    .navbar-logout:hover {
        text-decoration-line: none;
    }
</style>
<div class="modal fade" id="logOut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel" style="color:#000"><i class="fa fa-sign-out fa-1x"></i> LOG OUT?</h4>
            </div>
            <div class="modal-body" style="color:#000">
                <p>ARE YOU SURE YOU WANT TO LOG OUT?</p>
            </div>
            <div class="modal-footer" id=""> 
                <a href="<?php echo $this->Url->build(["controller" => "Users", "action" => "logout"]); ?>" class="btn btn-primary" id="logout">YES</a>
                <a class="btn btn-default" href="#" data-dismiss="modal">NO</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel" style="color:#000">DELETE</h4>
            </div>
            <div class="modal-body" style="color : #000;">
                <p>ARE YOU SURE WANT TO DELETE THIS RECORD ?</p>
            </div>
            <div class="modal-footer" id=""> <a href="#" class="btn btn-danger confirmyes" id="delete" data-dismiss="modal">YES</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deletesms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">DELETE SMS</h4>
            </div>
            <div class="modal-body">
                <p>ARE YOU SURE WANT TO DELETE All SELECTED RECORDS ?</p>
            </div>
            <div class="modal-footer" id="">
                <a href="#" class="btn btn-primary deletesms_cnfirm" id="delete" data-dismiss="modal">YES</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">DELETE SMS</h4>
            </div>
            <div class="modal-body">
                <p>PLEASE SELECT AT LEAST ONE CHECK BOX! </p>
            </div>
            <div class="modal-footer" id=""> 
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteuser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">DELETE</h4>
            </div>
            <div class="modal-body">
                <p>RECORD CAN NOT BE DELETE. YOU ARE ALREADY LOG IN! </p>
            </div>
            <div class="modal-footer" id=""> 
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<div class="navbar navbar-default yamm desktopview" style="border: none;">
    <div class="col-md-1">
        <div class="navbar-header">
            <button type="button" data-toggle="collapse" data-target="#navbar-collapse-grid" class="navbar-toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class = "navbar-brand" href="<?php echo $this->Url->build(["controller" => "Dashboard", "action" => "index"]); ?>">
                <span style="font-size:28px;">H-MEN</span>
            </a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="menu-items">
            <div id="navbar-collapse-grid" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="<?php echo ($this->name == 'Dashboard' && in_array($this->request->action, array('index'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "Dashboard", "action" => "index"]); ?>">
                            <i class="fa fa-home fa-fw"></i>DASHBOARD
                        </a>
                    </li>
                    <li class="<?php echo (in_array($this->name, array('Services', 'ServiceCategory'))) ? $active : ""; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href=""><i class="fa fa-cogs fa-fw"></i> SERVICES <i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li class="<?php echo (in_array($this->name, array('ServiceCategory'))) ? $selected : ""; ?>"><a href="<?php echo $this->Url->build(["controller" => "ServiceCategory", "action" => "index"]); ?>"><i class="fa fa-tags"></i> &nbsp;Categories</a> </li>
                            <li class="divider"></li>
                            <li class="<?php echo (in_array($this->name, array('Services'))) ? $selected : ""; ?>"><a href="<?php echo $this->Url->build(["controller" => "Services", "action" => "index"]); ?>"><i class="fa fa-asterisk"></i> &nbsp;Services</a> </li>
                        </ul>
                    </li>
                    <li class="<?php echo (in_array($this->name, array('Users'))) ? $active : ""; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href=""><i class="fa fa-users fa-fw"></i> USERS <i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <?php if (isset($userType) && in_array($userType, ['ADMIN'])) { ?>
                                <li class=""><a href="<?php echo $this->Url->build(["controller" => "Users", "action" => "index"]); ?>"><i class="fa fa-user"></i> &nbsp;HMEN USERS</a> </li>
                                <li class="divider"></li>
                            <?php } ?>
                            <li class=""><a href="<?php echo $this->Url->build(["controller" => "Users", "action" => "appuser"]); ?>"><i class="fa fa-user"></i> &nbsp;APP USERS</a> </li>
                            <li class="divider"></li>
                            <li class="<?php echo ($this->name == 'Members' && in_array($this->request->action, array('index', 'add', 'edit'))) ? $active : ""; ?>"><a href="<?php echo $this->Url->build(["controller" => "Members", "action" => "index"]); ?>"><i class="fa fa-user"></i> &nbsp;MEMBERS</a> </li>
                            <li class="divider"></li>
                            <li class="<?php echo ($this->name == 'Vendors' && in_array($this->request->action, array('index', 'add', 'edit'))) ? $active : ""; ?>"><a href="<?php echo $this->Url->build(["controller" => "Vendors", "action" => "index"]); ?>"><i class="fa fa-user"></i> &nbsp;VENDORS</a> </li>
                        </ul>
                    </li>
<!--                    <li class="<?php echo ($this->name == 'Members' && in_array($this->request->action, array('index', 'add', 'edit'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "Members", "action" => "index"]); ?>">
                            <i class="fa fa-user fa-fw"></i> MEMBERS
                        </a>
                    </li>-->
                    <li class="<?php echo ($this->name == 'Orders' && in_array($this->request->action, array('index', 'add', 'edit'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "Orders", "action" => "index"]); ?>">
                            <i class="fa fa-shopping-cart fa-fw"></i> ORDERS
                        </a>
                    </li>
                    <li class="<?php echo ($this->name == 'PackageServices' && in_array($this->request->action, array('index', 'edit'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "PackageServices", "action" => "index"]); ?>">
                            <i class="fa fa-gift fa-fw"></i> PACKAGE SERVICES
                        </a>
                    </li>
                    <li class="<?php echo ($this->name == 'Settings') ? $active : ""; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href=""><i class="fa fa-gears fa-fw"></i> SETTINGS <i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li class=""><a href="<?php echo $this->Url->build(["controller" => "Settings", "action" => "banner"]); ?>"><i class="fa fa-file-image-o"></i> &nbsp;BANNERS</a> </li>
                            <li class="divider"></li>
                            <li class=""><a href="<?php echo $this->Url->build(["controller" => "Settings", "action" => "faq"]); ?>"><i class="fa fa-question-circle"></i> &nbsp;FAQS</a> </li>
                            <li class="divider"></li>
                            <li class=""><a href="<?php echo $this->Url->build(["controller" => "Settings", "action" => "coupon"]); ?>"><i class="fa fa-tag"></i> &nbsp;COUPON</a> </li>
                        </ul>
                    </li>
                    <li class="<?php echo ($this->name == 'Surveys' && in_array($this->request->action, array('index', 'view', 'edit'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "Surveys", "action" => "index"]); ?>">
                            <i class="fa fa-user fa-fw"></i> SURVEYS
                        </a>
                    </li>
                    <li class="<?php echo ($this->name == 'Reports' && in_array($this->request->action, array('index', 'salesperformance', 'surveys'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "index"]); ?>">
                            <i class="fa fa-book fa-fw"></i> REPORTS
                        </a>
                    </li>
                </ul>   
            </div> 
        </div>
    </div>
    <div class="col-md-1"></div>

    <div class="col-md-2">
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">
                    <i class="fa fa-user fa-fw"></i> <?= $this->request->session()->read('Auth.User.name'); ?> <i class="fa fa-caret-down"></i> 
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a class="navbar-logout" href="#" data-target="#logOut" data-toggle="modal"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<div class="navbar navbar-default yamm mobileview">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target="#nav1">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="#"> <span style="font-size:28px;">H-MEN</span> </a>
            <div class="nav-collapse collapse" id="nav1">
                <ul class="nav">
                    <li>
                        <a href="<?php echo $this->Url->build(["controller" => "Dashboard", "action" => "index"]); ?>" class="dropdown-toggle"> <i class="fa fa-home fa-fw"></i>DASHBOARD </a>
                    </li>
                    <li class="<?php echo (in_array($this->name, array('Services', 'ServiceCategory'))) ? $active : ""; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href=""><i class="fa fa-cogs fa-fw"></i> SERVICES <i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li class="<?php echo (in_array($this->name, array('ServiceCategory'))) ? $selected : ""; ?>"><a href="<?php echo $this->Url->build(["controller" => "ServiceCategory", "action" => "index"]); ?>"><i class="fa fa-tags"></i> &nbsp;Categories</a> </li>
                            <li class="divider"></li>
                            <li class="<?php echo (in_array($this->name, array('Services'))) ? $selected : ""; ?>"><a href="<?php echo $this->Url->build(["controller" => "Services", "action" => "index"]); ?>"><i class="fa fa-asterisk"></i> &nbsp;Services</a> </li>
                        </ul>
                    </li>
                    <li class="<?php echo (in_array($this->name, array('Users'))) ? $active : ""; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href=""><i class="fa fa-users fa-fw"></i> USERS <i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <?php if (isset($userType) && in_array($userType, ['ADMIN'])) { ?>
                                <li class=""><a href="<?php echo $this->Url->build(["controller" => "Users", "action" => "index"]); ?>"><i class="fa fa-user"></i> &nbsp;HMEN USERS</a> </li>
                                <li class="divider"></li>
                            <?php } ?>
                            <li class=""><a href="<?php echo $this->Url->build(["controller" => "Users", "action" => "appuser"]); ?>"><i class="fa fa-user"></i> &nbsp;APP USERS</a> </li>
                            <li class="divider"></li>
                            <li class="<?php echo ($this->name == 'Members' && in_array($this->request->action, array('index', 'add', 'edit'))) ? $active : ""; ?>"><a href="<?php echo $this->Url->build(["controller" => "Members", "action" => "index"]); ?>"><i class="fa fa-user"></i> &nbsp;MEMBERS</a> </li>
                            <li class="divider"></li>
                            <li class="<?php echo ($this->name == 'Vendors' && in_array($this->request->action, array('index', 'add', 'edit'))) ? $active : ""; ?>"><a href="<?php echo $this->Url->build(["controller" => "Vendors", "action" => "index"]); ?>"><i class="fa fa-user"></i> &nbsp;VENDORS</a> </li>
                        </ul>
                    </li>
<!--                    <li class="<?php echo ($this->name == 'Members' && in_array($this->request->action, array('index', 'add', 'edit'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "Members", "action" => "index"]); ?>">
                            <i class="fa fa-user fa-fw"></i> MEMBERS
                        </a>
                    </li>-->
                    <li class="<?php echo ($this->name == 'Orders' && in_array($this->request->action, array('index', 'add', 'edit'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "Orders", "action" => "index"]); ?>">
                            <i class="fa fa-shopping-cart fa-fw"></i> ORDERS
                        </a>
                    </li>
                    <li class="<?php echo ($this->name == 'PackageServices' && in_array($this->request->action, array('index', 'edit'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "PackageServices", "action" => "index"]); ?>">
                            <i class="fa fa-gift fa-fw"></i> PACKAGE SERVICES
                        </a>
                    </li>
                    <li class="<?php echo ($this->name == 'Settings') ? $active : ""; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href=""><i class="fa fa-gears fa-fw"></i> SETTINGS <i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li class=""><a href="<?php echo $this->Url->build(["controller" => "Settings", "action" => "banner"]); ?>"><i class="fa fa-file-image-o"></i> &nbsp;BANNERS</a> </li>
                            <li class="divider"></li>
                            <li class=""><a href="<?php echo $this->Url->build(["controller" => "Settings", "action" => "faq"]); ?>"><i class="fa fa-question-circle"></i> &nbsp;FAQS</a> </li>
                            <li class="divider"></li>
                            <li class=""><a href="<?php echo $this->Url->build(["controller" => "Settings", "action" => "coupon"]); ?>"><i class="fa fa-tag"></i> &nbsp;COUPON</a> </li>
                        </ul>
                    </li>
                    <li class="<?php echo ($this->name == 'Surveys' && in_array($this->request->action, array('index', 'view', 'edit'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "Surveys", "action" => "index"]); ?>">
                            <i class="fa fa-user fa-fw"></i> SURVEYS
                        </a>
                    </li>
                    <li class="<?php echo ($this->name == 'Reports' && in_array($this->request->action, array('index', 'salesperformance', 'surveys'))) ? $active : ""; ?>">
                        <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "index"]); ?>">
                            <i class="fa fa-book fa-fw"></i> REPORTS
                        </a>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">
                            <i class="fa fa-user fa-fw"></i> <?= $this->request->session()->read('Auth.User.name'); ?> <i class="fa fa-caret-down"></i> 
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li>
                                <a class="navbar-logout" href="#" data-target="#logOut" data-toggle="modal"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>