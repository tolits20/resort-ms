<?php 
include("../../resources/database/config.php"); 
include('../includes/template.php');

?>
  <div id="main-content" class="container mt-5">
    <h2 class="text-center mb-4">My Tasks Dashboard</h2>
    
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-primary">My Assigned Tasks</h4>
            <div>
                <span class="badge badge-pill badge-light mr-2">5 Total Tasks</span>
                <span class="badge badge-pill badge-soft-danger mr-2">2 Overdue</span>
                <span class="badge badge-pill badge-soft-warning">3 Pending</span>
            </div>
        </div>
        
        <div class="card-body bg-white p-4">
            <div class="form-row mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0">
                                <i class="fa fa-search text-muted"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control border-left-0" placeholder="Search my tasks...">
                    </div>
                </div>
                <div class="col-md-3 ml-auto">
                    <select class="form-control bg-light border-0">
                        <option>All Statuses</option>
                        <option>Pending</option>
                        <option>In Progress</option>
                        <option>Completed</option>
                    </select>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-top-0 text-muted font-weight-normal">Task ID</th>
                            <th class="border-top-0 text-muted font-weight-normal">Task Name</th>
                            <th class="border-top-0 text-muted font-weight-normal">Due Date</th>
                            <th class="border-top-0 text-muted font-weight-normal">Priority</th>
                            <th class="border-top-0 text-muted font-weight-normal">Status</th>
                            <th class="border-top-0 text-muted font-weight-normal">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Overdue Tasks -->
                        <tr class="border-soft">
                            <td class="align-middle">1</td>
                            <td class="align-middle font-weight-medium">Clean Pool</td>
                            <td class="align-middle">
                                <span class="badge badge-soft-danger p-2">Overdue: 2023-10-15</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-warning p-2">Medium</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-secondary p-2">Pending</span>
                            </td>
                            <td class="align-middle">
                                <button class="btn btn-soft-success btn-sm rounded-pill mr-1" title="Mark as Complete">Complete</button>
                                <button class="btn btn-soft-info btn-sm rounded-pill" title="View Details">Details</button>
                            </td>
                        </tr>
                        <tr class="border-soft">
                            <td class="align-middle">2</td>
                            <td class="align-middle font-weight-medium">Fix Fence</td>
                            <td class="align-middle">
                                <span class="badge badge-soft-danger p-2">Overdue: 2023-10-10</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-danger p-2">High</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-primary p-2">In Progress</span>
                            </td>
                            <td class="align-middle">
                                <button class="btn btn-soft-success btn-sm rounded-pill mr-1" title="Mark as Complete">Complete</button>
                                <button class="btn btn-soft-info btn-sm rounded-pill" title="View Details">Details</button>
                            </td>
                        </tr>
                        
                        <!-- Upcoming Tasks -->
                        <tr class="border-soft">
                            <td class="align-middle">3</td>
                            <td class="align-middle font-weight-medium">Mow Lawn</td>
                            <td class="align-middle">
                                <span class="badge badge-soft-warning p-2">Due Soon: 2023-10-20</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-info p-2">Low</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-secondary p-2">Pending</span>
                            </td>
                            <td class="align-middle">
                                <button class="btn btn-soft-success btn-sm rounded-pill mr-1" title="Mark as Complete">Complete</button>
                                <button class="btn btn-soft-info btn-sm rounded-pill" title="View Details">Details</button>
                            </td>
                        </tr>
                        <tr class="border-soft">
                            <td class="align-middle">4</td>
                            <td class="align-middle font-weight-medium">Trim Hedges</td>
                            <td class="align-middle">
                                <span class="badge badge-soft-success p-2">Due: 2023-10-25</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-warning p-2">Medium</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-secondary p-2">Pending</span>
                            </td>
                            <td class="align-middle">
                                <button class="btn btn-soft-success btn-sm rounded-pill mr-1" title="Mark as Complete">Complete</button>
                                <button class="btn btn-soft-info btn-sm rounded-pill" title="View Details">Details</button>
                            </td>
                        </tr>
                        <tr class="border-soft">
                            <td class="align-middle">5</td>
                            <td class="align-middle font-weight-medium">Repair Deck</td>
                            <td class="align-middle">
                                <span class="badge badge-soft-success p-2">Due: 2023-11-05</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-danger p-2">High</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-soft-warning p-2">Not Started</span>
                            </td>
                            <td class="align-middle">
                                <button class="btn btn-soft-success btn-sm rounded-pill mr-1" title="Mark as Complete">Complete</button>
                                <button class="btn btn-soft-info btn-sm rounded-pill" title="View Details">Details</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span class="text-muted">Showing all 5 of your assigned tasks</span>
                </div>
                <div>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fa fa-download mr-1"></i> Export Tasks
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Softer Color Palette */
        .badge-soft-danger {
            background-color: rgba(247, 79, 112, 0.15);
            color: #f74f70;
        }
        
        .badge-soft-warning {
            background-color: rgba(247, 178, 71, 0.15);
            color: #f7b247;
        }
        
        .badge-soft-success {
            background-color: rgba(61, 188, 147, 0.15);
            color: #3dbc93;
        }
        
        .badge-soft-primary {
            background-color: rgba(71, 132, 247, 0.15);
            color: #4784f7;
        }
        
        .badge-soft-secondary {
            background-color: rgba(142, 142, 142, 0.15);
            color: #8e8e8e;
        }
        
        .badge-soft-info {
            background-color: rgba(57, 175, 209, 0.15);
            color: #39afd1;
        }
        
        /* Soft Buttons */
        .btn-soft-success {
            background-color: rgba(61, 188, 147, 0.15);
            color: #3dbc93;
            border: none;
        }
        
        .btn-soft-success:hover {
            background-color: rgba(61, 188, 147, 0.25);
            color: #3dbc93;
        }
        
        .btn-soft-info {
            background-color: rgba(57, 175, 209, 0.15);
            color: #39afd1;
            border: none;
        }
        
        .btn-soft-info:hover {
            background-color: rgba(57, 175, 209, 0.25);
            color: #39afd1;
        }
        
        /* Softer Borders */
        .border-soft {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .table td, .table th {
            border-top: none;
            padding: 1rem 0.75rem;
        }
        
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .badge-pill {
            border-radius: 50rem;
        }
        
        .font-weight-medium {
            font-weight: 500;
        }
        
        .rounded-lg {
            border-radius: 0.5rem !important;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
    </style>
</div>