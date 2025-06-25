<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card_style card_style-user h-100">
                <div class="card_style-user-head">
                    <div class="card_style-user-profile-content">
                        <a href="{{ route('developers.dashboard') }}" class="btn btn-light btn-sm">Dashboard</a>
                        <a href="{{ route('developers.webhooks') }}" class="btn btn-light btn-sm">Webhooks</a>
                        <a href="{{ route('developers.api-tokens') }}" class="btn btn-primary btn-sm">Api Tokens</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4 mt-4">
            <div class="card_style card_style-user h-100">
                <div class="card_style-user-head">
                    <div class="card_style-user-profile-content">
                        <h4>Create Token</h4>
                    </div>
                </div>
                <div class="card_style-user-body mt-3">
                    <div class="card_style-tasks text-center">
                        <div class="card_style-tasks-title">
                            <div>
                                <label for="">Token Name</label>
                                <input type="text" wire:model="tokenName" class="form-control" placeholder="Enter Token Name">
                                @error('tokenName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3">
                                <label for="">Token Ability</label>
                                <select wire:model="tokenAbilities" class="form-control" multiple>
                                    <option value="webhooks-read" selected>Webhooks Read</option>
                                    <option value="webhooks-create" >Webhooks Create</option>
                                    <option value="webhooks-update" disabled>Webhooks Update</option>
                                    <option value="webhooks-delete" disabled>Webhooks Delete</option>
                                </select>
                                @error('tokenAbilty') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3">
                                <button wire:click="createToken" class="btn btn-primary">Create Token</button>
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
                        <h4>Tokens</h4>
                    </div>
                </div>
                <div class="card_style-user-body mt-3">
                    <div class="card_style-tasks text-center">
                        <div class="card_style-tasks-title">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Token Name</th>
                                        <th>Token Ability</th>
                                        <th>Created Date</th>
                                        <th>Last Used At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tokens as $token)
                                        <tr>
                                            <td>{{ $token->name }}</td>
                                            <td>
                                                @foreach($token->abilities as $ability)
                                                    <span class="badge badge-primary">{{ $ability }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $token->created_at->diffForHumans() }}</td>
                                            <td>{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never Used' }}</td>
                                            <td>
                                                <button wire:click="deleteToken({{ $token->id }})"  class="btn-icon" ><i class="bx bx-trash"></i></button>
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

