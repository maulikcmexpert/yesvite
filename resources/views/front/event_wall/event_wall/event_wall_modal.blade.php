    <!-- Modal -->
    @foreach ($postList as $post)
        {{-- {{dd($post);}} --}}
        <div class="modal fade create-post-modal all-events-filtermodal" id="reaction-modal-{{ $post['id'] }}"
            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Reactions</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="reactions-info-main event-center-tabs-main">
                            <!-- ===tabs=== -->
                            <nav>
                                <div class="nav nav-tabs reaction-nav-tabs" id="nav-tab-{{ $post['id'] }}"
                                    role="tablist">
                                    <!-- All Reactions Tab -->
                                    <button class="nav-link active" id="nav-all-reaction-tab-{{ $post['id'] }}"
                                        data-bs-toggle="tab" data-bs-target="#nav-all-reaction-{{ $post['id'] }}"
                                        type="button" role="tab" aria-controls="nav-all-reaction"
                                        aria-selected="true">
                                        All {{ count($post['reactionList']) }}
                                    </button>

                                    <!-- Individual Reaction Tabs -->
                                    @php
                                        // Define icons for reactions
                                        $reactionIcons = [
                                            "\\u{2764}" => asset('assets/front/img/heart-emoji.png'), // ❤️
                                            "\\u{1F44D}" => asset('assets/front/img/thumb-icon.png'), // 👍
                                            "\\u{1F604}" => asset('assets/front/img/smily-emoji.png'), // 😄
                                            "\\u{1F60D}" => asset('assets/front/img/eye-heart-emoji.png'), // 😍
                                            "\\u{1F44F}" => asset('assets/front/img/clap-icon.png'), // 👏
                                        ];
                                    @endphp

                                    @foreach (array_count_values($post['reactionList']) as $reaction => $count)
                                        <button class="nav-link"
                                            id="nav-{{ $reaction }}-reaction-tab-{{ $post['id'] }}"
                                            data-bs-toggle="tab"
                                            data-bs-target="#nav-{{ $reaction }}-reaction-{{ $post['id'] }}"
                                            type="button" role="tab"
                                            aria-controls="nav-{{ $reaction }}-reaction" aria-selected="false">
                                            <img src="{{ $reactionIcons[$reaction] ?? asset('assets/front/img/heart-emoji.png') }}"
                                                alt="{{ $reaction }}" loading="lazy">
                                            {{ $count }}
                                        </button>
                                    @endforeach
                                </div>
                            </nav>

                            <!-- ===tabs=== -->

                            <!-- ===Tab-content=== -->
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade active show nav-all-reaction-tab-{{ $post['id'] }}"
                                    id="nav-all-reaction" role="tabpanel"
                                    aria-labelledby="nav-all-reaction-tab-{{ $post['id'] }}">
                                    <ul>
                                        @php
                                            // Define reaction icons mapping
                                            $reactionIcons = [
                                                "\\u{2764}" => asset('assets/front/img/heart-emoji.png'), // ❤️
                                                "\\u{1F44D}" => asset('assets/front/img/thumb-icon.png'), // 👍
                                                "\\u{1F604}" => asset('assets/front/img/smily-emoji.png'), // 😄
                                                "\\u{1F60D}" => asset('assets/front/img/eye-heart-emoji.png'), // 😍
                                                "\\u{1F44F}" => asset('assets/front/img/clap-icon.png'), // 👏
                                            ];
                                        @endphp
                                        @foreach ($post['reactionList'] as $reaction)
                                            <li class="reaction-info-wrp">
                                                <div class="commented-user-head">
                                                    <!-- User Profile Section -->
                                                    <div class="commented-user-profile">
                                                        <div class="commented-user-profile-img">
                                                            @if ($post['profile'] != '')
                                                                <img src="{{ $post['profile'] ? asset($post['profile']) : asset('assets/front/img/default-profile.png') }}"
                                                                    alt="{{ $post['username'] }}" loading="lazy">
                                                            @else
                                                                @php

                                                                    // $parts = explode(" ", $name);
                                                                    $nameParts = explode(' ', $post['username']);
                                                                    $firstInitial = isset($nameParts[0][0])
                                                                        ? strtoupper($nameParts[0][0])
                                                                        : '';
                                                                    $secondInitial = isset($nameParts[1][0])
                                                                        ? strtoupper($nameParts[1][0])
                                                                        : '';
                                                                    $initials = $firstInitial . $secondInitial;

                                                                    // Generate a font color class based on the first initial
                                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                                @endphp
                                                                <h5 class="{{ $fontColor }}">
                                                                    {{ $initials }}
                                                                </h5>
                                                            @endif

                                                        </div>
                                                        <div class="commented-user-profile-content">
                                                            <h3>{{ $post['username'] }}</h3>
                                                            <p>{{ $post['location'] }}</p>
                                                        </div>
                                                    </div>
                                                    <!-- Reaction Emoji Section -->
                                                    <div
                                                        class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                        <img src="{{ $reactionIcons[$reaction] ?? asset('assets/front/img/heart-emoji.png') }}"
                                                            alt="{{ $reaction }}" loading="lazy">
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>

                            </div>
                            <!-- ===Tab-content=== -->
                        </div>
                    </div>
                    {{-- <div class="modal-footer">
                        <button type="button" class="cmn-btn reset-btn">Reset</button>
                        <button type="button" class="cmn-btn">Apply</button>
                    </div> --}}
                </div>
            </div>
        </div>
    @endforeach