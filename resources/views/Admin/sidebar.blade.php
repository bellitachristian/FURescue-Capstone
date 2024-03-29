<ul class="navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Admin</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a class="nav-link" href="{{route('admindash')}}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Role:Admin
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Application Request</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Custom Funtions:</h6>
            <a class="collapse-item" href="{{route('viewshelter')}}">Animal Shelter</a>
            <a class="collapse-item" href="{{route('viewpetowner')}}">Pet Owner</a>
        </div>
    </div>
</li>

<!-- Nav Item - Utilities Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
        aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Reactivation</span>
    </a>
    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Custom Functions:</h6>
            <a class="collapse-item" href="{{route('view.reactivation')}}">Animal Shelter</a>
            <a class="collapse-item" href="{{route('view.reactivation.petowner')}}">Pet Owner</a>
            <a class="collapse-item" href="{{route('view.reactivation.adopter')}}">Adopter</a>
        </div>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
        aria-expanded="true" aria-controls="collapsePages">
        <i class="fas fa-fw fa-paw"></i>
        <span>Transaction</span>
    </a>
    <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Facilities</h6>
            <!-- <a class="collapse-item" href="{{route('view.proof.payment')}}">Subscription</a> -->
            <a class="collapse-item" href="{{route('adoption.payment')}}">Adoption Payment</a>
            <a class="collapse-item" href="{{route('view.transfer')}}">Transfer Payment</a>
        </div>
    </div>
</li>


<!-- Divider -->
<hr class="sidebar-divider">


<li class="nav-item">
    <a class="nav-link" href="{{route('view.subscription')}}">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Subscription</span></a>
</li>

<!-- Nav Item - Tables
<li class="nav-item">
    <a class="nav-link" href="tables.html">
        <i class="fas fa-fw fa-table"></i>
        <span>Revenue</span></a>
</li> -->

<!-- Nav Item - Tables -->
<li class="nav-item">
    <a class="nav-link" href="{{route('view.reports.admin')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Reports</span></a>
</li>


<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>