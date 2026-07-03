@php
    $node = $node ?? [];
    $user = is_object($node) ? $node : (object)$node;
    
    $leftChild = $node->left ?? $node['left'] ?? null;
    $rightChild = $node->right ?? $node['right'] ?? null;
    
    $userId = $user->user_id ?? $user->id ?? 0;
    $firstName = $user->first_name ?? 'User';
    $lastName = $user->last_name ?? '';
    $userName = $user->user_name ?? 'N/A';
    $isActive = $user->is_active ?? false;
    $isRoot = $isRoot ?? false;
    $depth = $depth ?? 0;
    
    $avatarClass = $isRoot ? 'avatar-blue' : 'avatar-orange';
    $subtreeId = 'subtree-' . $userId . '-' . $depth;
    
    $hasChildren = !empty($leftChild) || !empty($rightChild);
@endphp

<div class="tree-node-wrapper">
    <div class="user-card profile-clickable {{ $isRoot ? 'root-user' : '' }}" 
         data-user-id="{{ $userId }}"
         style="cursor: pointer;">
        
        @if($isRoot)
            <i class="las la-crown crown-icon" title="Root User (You)"></i>
        @endif
        
        <div class="user-avatar {{ $avatarClass }}">
            {{ strtoupper(substr($firstName, 0, 1)) }}{{ strtoupper(substr($lastName, 0, 1)) }}
        </div>
        
        <div class="user-name">{{ $firstName }} {{ $lastName }}</div>
        <div class="user-handle">{{ '@' }}{{ $userName }}</div>
        
        <div class="status-badge">
            <span class="status-dot" style="background: {{ $isActive ? '#27ae60' : '#dc3545' }}"></span>
            {{ $isActive ? 'Active' : 'Inactive' }}
        </div>
    </div>

    @if($hasChildren)
        <div class="connector-vertical"></div>
        <div class="expand-btn subtree-toggle" 
             data-target="{{ $subtreeId }}"
             style="cursor: pointer;">
            <i class="las la-chevron-down"></i>
        </div>

        {{-- Root's children are shown by default, deeper levels hidden --}}
        <div id="{{ $subtreeId }}" class="subtree {{ $isRoot ? 'show' : '' }}">
            <div class="tree-level">
                <div class="tree-node-wrapper">
                    @if($leftChild)
                        @include('pages.user.partials.tree-node', ['node' => $leftChild, 'isRoot' => false, 'depth' => $depth + 1])
                    @else
                        <div class="empty-slot">
                            <i class="las la-user"></i>
                            <div>Empty Slot (LS)</div>
                        </div>
                    @endif
                </div>
                
                <div class="tree-node-wrapper">
                    @if($rightChild)
                        @include('pages.user.partials.tree-node', ['node' => $rightChild, 'isRoot' => false, 'depth' => $depth + 1])
                    @else
                        <div class="empty-slot">
                            <i class="las la-user"></i>
                            <div>Empty Slot (RS)</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @elseif($isRoot)
        {{-- Root with no children - show empty slots --}}
        <div class="connector-vertical"></div>
        <div class="subtree show">
            <div class="tree-level">
                <div class="tree-node-wrapper">
                    <div class="empty-slot">
                        <i class="las la-user"></i>
                        <div>Empty Slot (LS)</div>
                    </div>
                </div>
                <div class="tree-node-wrapper">
                    <div class="empty-slot">
                        <i class="las la-user"></i>
                        <div>Empty Slot (RS)</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>