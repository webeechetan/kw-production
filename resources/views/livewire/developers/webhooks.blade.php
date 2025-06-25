<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card_style card_style-user h-100">
                <div class="card_style-user-head">
                    <div class="card_style-user-profile-content">
                        <a href="{{ route('developers.dashboard') }}" class="btn btn-light btn-sm">Dashboard</a>
                        <a href="{{ route('developers.webhooks') }}" class="btn btn-primary btn-sm">Webhooks</a>
                        <a href="{{ route('developers.api-tokens') }}" class="btn btn-light btn-sm">Api Tokens</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4 mt-4">
            <div class="card_style card_style-user h-100">
                <div class="card_style-user-head">
                    <div class="card_style-user-profile-content">
                        <h4>Create Webhook</h4>
                    </div>
                </div>
                <div class="card_style-user-body mt-3">
                    <div class="card_style-tasks text-center">
                        <div class="card_style-tasks-title">
                            <div>
                                <label for="">Webhook Name</label>
                                <input type="text" wire:model="webhookName" class="form-control" placeholder="Enter Webhook Name">
                                @error('webhookName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3">
                                <label for="">Webhook Type</label>
                                <select wire:model="webhookType" class="form-control">
                                    <option value="outgoing" selected>Outgoing</option>
                                    <option value="incoming" disabled>Incoming</option>
                                </select>
                                @error('webhookType') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3">
                                <label for="">Webhook For</label>
                                <select wire:model="webhookFor" class="form-control mt-2">
                                    <option value="task" selected>Task</option>
                                    <option value="client" disabled>Client</option>
                                    <option value="project" disabled>Project</option>
                                    <option value="team" disabled>Team</option>
                                    <option value="user" disabled>User</option>
                                </select>
                                @error('webhookFor') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3">
                                <label for="">Webhook Event</label>
                                <select wire:model="webhookEvent" class="form-control mt-2">
                                    <option value="created" selected>Create</option>
                                    <option value="updated" disabled>Update</option>
                                    <option value="deleted" disabled>Delete</option>
                                </select>
                                @error('webhookEvent') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3">
                                <label for="">Webhook URL</label>
                                <input type="text" wire:model="webhookUrl" class="form-control" placeholder="Enter Webhook URL">
                                @error('webhookUrl') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3">
                                <button wire:click="createWebhook" class="btn btn-primary">Create Webhook</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 mb-4 mt-4">
            <div class="card_style card_style-user h-100">
                <div class="card_style-user-head">
                    <div class="card_style-user-profile-content">
                        <h4>Webhooks</h4>
                    </div>
                </div>
                <div class="card_style-user-body mt-3">
                    <div class="card_style-tasks text-center">
                        <div class="card_style-tasks-title">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Webhook Name</th>
                                        <th>Webhook Type</th>
                                        <th>Webhook For</th>
                                        <th>Webhook Event</th>
                                        <th>Webhook URL</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($webhooks as $webhook)
                                        <tr>
                                            <td>{{ $webhook->name }}</td>
                                            <td>{{ $webhook->type }}</td>
                                            <td>{{ $webhook->for }}</td>
                                            <td>{{ $webhook->event }}</td>
                                            <td>{{ $webhook->url }}</td>
                                            <td>
                                                <button wire:click="deleteWebhook({{$webhook->id}})" type="button" class="btn-icon" ><i class="bx bx-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
