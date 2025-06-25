<div class="container">
  <!-- Dashboard Header -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a wire:navigate href="{{ route('dashboard') }}">
          <i class='bx bx-line-chart'></i> Dashboard </a>
      </li>
      <li class="breadcrumb-item">
        <a wire:navigate href="{{ route('team.index') }}">All Team</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">Tech Team</li>
    </ol>
  </nav>
  <div class="dashboard-head mb-4">
    <div class="row align-items-center">
      <div class="col">
        <div class="dashboard-head-title-wrap">
          <div class="client_head_logo">
            <img src="" alt="">
          </div>
          <div>
            <h3 class="main-body-header-title mb-2">Tech Team</h3>
            <div class="row align-items-center">
              <div class="col">
                <span>
                  <i class="bx bx-layer text-secondary"></i>
                </span> Created By
              </div>
              <div class="col text-secondary text-nowrap">Auto Generated</div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-end col">
        <div class="main-body-header-right">
          <a data-bs-toggle="modal" data-bs-target="#add-team-modal" href="javascript:void(0);" class="btn-sm btn-border btn-border-primary">
            <i class='bx bx-plus'></i> Add Team </a>
        </div>
      </div>
    </div>
  </div>
  <!--- Modal --->
  <div>
    <div class="modal fade" id="add-team-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header d-flex align-items-center justify-content-between gap-20">
            <h3 class="modal-title">
              <span class="btn-icon btn-icon-primary me-1">
                <i class='bx bx-layer'></i>
              </span>
              <span class="project-form-text">Add Member</span>
            </h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" enctype="multipart/form-data">
              <div class="modal-form-body">
                <div class="row">
                  <div class="col-md-4 mb-4 mb-md-0">
                    <label for="">Select Team <sup class="text-primary">*</sup>
                    </label>
                  </div>
                  <div class="col-md-8 mb-4">
                    <div class="custom-select">
                      <select wire:model.live="client_id" class="form-style">
                        <option value="">Select Team</option>
                        <option value="">Tech Team</option>
                        <option value="">Media Team</option>
                        <option value="">Design Team</option>
                        <option value="">Cs Team</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-4">
                    <label for=""> Manger Name <sup class="text-primary">*</sup>
                    </label>
                  </div>
                  <div class="col-md-8 mb-4">
                    <input type="text" class="form-style" placeholder="Name">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-4 mb-md-0">
                    <label for="">Start Date <sup class="text-primary">*</sup>
                    </label>
                  </div>
                  <div class="col mb-8 mb-4">
                    <a href="javascript:;" class="btn btn-sm w-100 btn-border-secondary project_start_date">
                      <i class='bx bx-calendar-alt'></i> Start Date </a>
                  </div>
                </div>
              </div>
              <div class="modal-form-btm">
                <div class="row">
                  <div class="col-md-6 ms-auto text-end">
                    <button type="submit" class="btn btn-primary project-form-btn">Add Member</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--- Dashboard Body --->
  <div class="row">
    <div class="col-lg-3">
      <div class="column-box states_style-progress h-100">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="states_style-icon">
              <i class='bx bx-layer'></i>
            </div>
          </div>
          <div class="col">
            <div class="row align-items-center g-2">
              <div class="col-auto">
                <h5 class="title-md mb-0">4</h5>
              </div>
              <div class="col-auto">
                <span class="font-400 text-grey">|</span>
              </div>
              <div class="col-auto">
                <div class="states_style-text">Project</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="column-box states_style-active h-100">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="states_style-icon">
              <i class='bx bx-layer'></i>
            </div>
          </div>
          <div class="col">
            <div class="row align-items-center g-2">
              <div class="col-auto">
                <h5 class="title-md mb-0"> 12 </h5>
              </div>
              <div class="col-auto">
                <span class="font-400 text-grey">|</span>
              </div>
              <div class="col-auto">
                <div class="states_style-text">Clients</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="column-box states_style-success h-100">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="states_style-icon">
              <i class='bx bx-layer'></i>
            </div>
          </div>
          <div class="col">
            <div class="row align-items-center g-2">
              <div class="col-auto">
                <h5 class="title-md mb-0">5</h5>
              </div>
              <div class="col-auto">
                <span class="font-400 text-grey">|</span>
              </div>
              <div class="col-auto">
                <div class="states_style-text">Members</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="column-box states_style-success h-100">
        <div class="row align-items-center">
          <div class="col-12">
            <div class="dashboard-head-title-wrap">
              <div class="states_style-icon me-3">
                <i class="bx bx-user"></i>
              </div>
              <div>
                <h3 class="main-body-header-title mb-2">Manager</h3>
                <div class="row align-items-center">
                  <div class="col"> Roshan Jajoriya </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 mt-4">
      <h3 class="main-body-header-title mb-3">Projects</h3>
    </div>
    <div class="row">
      <div class="col-lg-3">
        <div class="card_style h-100">
          <div class="card_style-head card_style-client-head">
            <div class="avatar avatar-sm avatar-pink" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Website Development">WD</div>
            <div>
              <h4 class="mb-1">
                <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">Website Development</a>
              </h4>
              <div>Acma</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card_style h-100">
          <div class="card_style-head card_style-client-head">
            <div class="avatar avatar-sm avatar-blue" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Dashboard">DB</div>
            <div>
              <h4 class="mb-0">
                <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">Dashboard</a>
              </h4>
              <div>True Consultant</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card_style h-100">
          <div class="card_style-head card_style-client-head">
            <div class="avatar avatar-sm avatar-green" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Time Management">TM</div>
            <div>
              <h4 class="mb-1">
                <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">Time Management</a>
              </h4>
              <div>Webeesocial</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card_style h-100">
          <div class="card_style-head card_style-client-head">
            <div class="avatar avatar-sm avatar-black" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ecommerce Website">EW</div>
            <div>
              <h4 class="mb-1">
                <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">Ecommerce Website</a>
              </h4>
              <div>Refresh Botanical</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 mt-4">
        <h3 class="main-body-header-title mb-3">Clients</h3>
      </div>
      <div class="row">
        <div class="col-lg-3 mb-3">
          <div class="card_style h-100">
            <div class="card_style-head card_style-client-head">
              <div class="avatar avatar-sm avatar-blue" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Acma">AC</div>
              <div>
                <h4 class="mb-0">
                  <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">ACMA</a>
                </h4>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 mb-3">
          <div class="card_style h-100">
            <div class="card_style-head card_style-client-head">
              <div class="avatar avatar-sm avatar-pink" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Kakelwalk">KW</div>
              <div>
                <h4 class="mb-0">
                  <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">Kakeywalk</a>
                </h4>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 mb-3">
          <div class="card_style h-100">
            <div class="card_style-head card_style-client-head">
              <div class="avatar avatar-sm avatar-yellow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Buyers Guide">BG</div>
              <div>
                <h4 class="mb-0">
                  <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">Buyers Guide</a>
                </h4>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 mb-3">
          <div class="card_style h-100">
            <div class="card_style-head card_style-client-head">
              <div class="avatar avatar-sm avatar-blue" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="True Consultant">TC</div>
              <div>
                <h4 class="mb-0">
                  <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">True Consultant</a>
                </h4>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card_style h-100">
            <div class="card_style-head card_style-client-head">
              <div class="avatar avatar-sm avatar-pink" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Dial-a Physio">DP</div>
              <div>
                <h4 class="mb-0">
                  <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">Dail a Physio</a>
                </h4>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card_style h-100">
            <div class="card_style-head card_style-client-head">
              <div class="avatar avatar-sm avatar-green" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Refresh Botanical">RB</div>
              <div>
                <h4 class="mb-0">
                  <a href="http://127.0.0.1:8000/project/view/701" wire:navigate="">Refresh Botanical</a>
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 mt-4">
          <h3 class="main-body-header-title mb-3">Members</h3>
        </div>
        <div class="row">
          <div class="col-lg-3 mb-3">
            <div class="card_style h-100">
              <div class="card_style-head card_style-client-head">
                <div class="team-member col-auto pe-0">
                  <a href="javascript:" class="avatar avatar-sm avatar-yellow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Ajay" data-bs-original-title="Ajay Kumar">AJ</a>
                </div>
                <div class="team-text col">
                  <h4 class="mb-1">Ajay</h4>
                  <div>Php Developer</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 mb-3">
            <div class="card_style h-100">
              <div class="card_style-head card_style-client-head">
                <div class="team-member col-auto pe-0">
                  <a href="javascript:" class="avatar avatar-sm avatar-blue" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Chetan" data-bs-original-title="Chetan Singh">CS</a>
                </div>
                <div class="team-text col">
                  <h4 class="mb-1">Chetan Singh</h4>
                  <div>Sr. Backend Developer</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 mb-3">
            <div class="card_style h-100">
              <div class="card_style-head card_style-client-head">
                <div class="team-member col-auto pe-0">
                  <a href="javascript:" class="avatar avatar-sm avatar-primary" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Roshan Jajoriya" data-bs-original-title="Roshan Jajoriya">RJ</a>
                </div>
                <div class="team-text col">
                  <h4 class="mb-1">Roshan Jajoriya</h4>
                  <div>Sr. UI Developer</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="card_style h-100">
              <div class="card_style-head card_style-client-head">
                <div class="team-member col-auto pe-0">
                  <a href="javascript:" class="avatar avatar-sm avatar-pink" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Himanshu Sharma" data-bs-original-title="Himanshu Sharma">HS</a>
                </div>
                <div class="team-text col">
                  <h4 class="mb-1">Himanshu Sharma</h4>
                  <div>Jr. Frontend Developer</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card_style h-100">
            <div class="card_style-head card_style-client-head">
              <div class="team-member col-auto pe-0">
                <a href="javascript:" class="avatar avatar-sm avatar-green" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Sharuakh" data-bs-original-title="Sharuakh">Sk</a>
              </div>
              <div class="team-text col">
                <h4 class="mb-1">Sharuakh</h4>
                <div>Frontend Developer</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>