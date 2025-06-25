<div>
    <div wire:ignore class="modal fade" id="team-stats-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"><span class="team_name">Team</span> Stats</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body team-stats-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
</div>

@script
    <script>
        if (annyang) {

            annyang.addCallback('soundstart', function() {
                // toastr.info('Listening...');
            });

            annyang.addCallback('result', function() {
                toastr.remove();
            });

            annyang.addCallback('error', function() {
                toastr.remove();
                toastr.error('An error occurred while listening. Please try again.');
            });

            annyang.addCallback('end', function() {
                toastr.remove();
            });

            // annyang.addCallback('resultMatch', function(userSaid, commandText, phrases) {
            //     toastr.remove();
            //     toastr.info(phrases);
            // });



            var commands = {
                'go to *route': function(route) {

                    console.log('go to '+route);

                    var task_route_combinations = ['task', 'tasks', 'task list', 'task list view', 'task add', 'task create', 'task edit', 'task update', 'task delete', 'task view', 'task details', 'task information', 'task information view', 'task information details'];
                    var project_route_combinations = ['project', 'projects', 'project list', 'project list view', 'project add', 'project create', 'project edit', 'project update', 'project delete', 'project view', 'project details', 'project information', 'project information view', 'project information details'];
                    var client_route_combinations = ['client', 'clients', 'client list', 'client list view', 'client add', 'client create', 'client edit', 'client update', 'client delete', 'client view', 'client details', 'client information', 'client information view', 'client information details'];
                    var team_route_combinations = ['team', 'teams', 'team list', 'team list view', 'team add', 'team create', 'team edit', 'team update', 'team delete', 'team view', 'team details', 'team information', 'team information view', 'team information details'];
                    var user_route_combinations = ['user', 'users', 'user list', 'user list view', 'user add', 'user create', 'user edit', 'user update', 'user delete', 'user view', 'user details', 'user information', 'user information view', 'user information details'];
                    var dashboard_route_combinations = ['dashboard', 'home', 'main', 'main page', 'main page view', 'main page details', 'main page information', 'main page information view', 'main page information details'];

                    if (task_route_combinations.includes(route)) {
                        route = 'tasks';
                    } else if (project_route_combinations.includes(route)) {
                        route = 'projects';
                    } else if (client_route_combinations.includes(route)) {
                        route = 'clients';
                    } else if (team_route_combinations.includes(route)) {
                        route = 'teams';
                    } else if (user_route_combinations.includes(route)) {
                        route = 'users';
                    } else if (dashboard_route_combinations.includes(route)) {
                        route = 'dashboard';
                    }

                    var routes = {
                        dashboard: "{{ route('dashboard') }}",
                        users: "{{ route('user.index') }}",
                        teams: "{{ route('team.index') }}",
                        clients: "{{ route('client.index') }}",
                        projects: "{{ route('project.index') }}",
                        tasks: "{{ route('task.index') }}",
                    };

                    if (routes[route]) {
                        window.location.href = routes[route];
                    }else{
                        toastr.error(route +' Route not found');
                    }
                },
                'create client *client': function(client) {
                    @this.createClient(client);
                },
                'create project :project for *client': function(project, client) {
                    console.log('create project '+project+' for '+client);
                    @this.createProject(project, client);
                },
                'status of *team': function(team) {
                    @this.getTeamStats(team);
                },
                'view client *client': function(client) {
                    @this.viewClient(client);
                },


            };

            annyang.setLanguage('en-IN');
            annyang.addCommands(commands);
            annyang.start();
        }

        document.addEventListener('command-success', event => {
            toastr.remove();
            toastr.success(event.detail);
        })

        document.addEventListener('command-error', event => {
            toastr.remove();
            toastr.error(event.detail);
        });

        document.addEventListener('client-created', event => {
            toastr.remove();
            toastr.success(event.detail);
            window.location.reload();
        });

        document.addEventListener('project-created', event => {
            toastr.remove();
            toastr.success(event.detail);
            window.location.reload();
        });

        document.addEventListener('team-stats', event => {

            let team_users = '';
            event.detail[0].users.forEach(user => {
                team_users += `<li class="list-group-item">`+user.name+`</li>`;
            });

            $('.team_name').text(event.detail[0].name);

            let team_stats = `
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">`+event.detail[0].name+`</p>   
                                <p class="card-text">`+event.detail[0].description+`</p>
                                <ul class="list-group">
                                    `+team_users+`
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            console.log(team_stats);
            
            $('.team-stats-body').html(team_stats);
            $('#team-stats-modal').modal('show');
        });
    </script>
@endscript
