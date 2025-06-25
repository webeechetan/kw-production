<div>
    <a href="javascript:" class="btn-sm btn-border btn-border-primary" wire:click="togglePin"
        wire:loading.attr="disabled">
        
        <span wire:loading wire:target="togglePin">
            <i class='bx bx-loader-alt bx-spin'></i>
        </span>
    
        <i class='bx bx-pin'></i>
        @if($pinned)
            Unpin
        @else
            Pin
        @endif
    </a>
</div>
