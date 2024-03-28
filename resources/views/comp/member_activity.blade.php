
            <div class="sideContents">
                <div class="sideContentsInner bg-white">

                    <div class="adminInformation">

                        <div class="company-logo">
                            <img src="{{ $member_act['comp_logo'] }}" alt="">
                        </div><!-- /.company-logo -->
                        <h2 class="admin-name">{{ $member_act['mem_name'] }}</h2>
                        <!--p class="admin-id">個人設定</p-->

                    </div><!-- /.adminInformation -->

                    <div class="adminInformation-sec">
                        
                        <div class="adminCharge">
                            <h2>メッセージ一覧</h2>
                            <ul class="list">
                                <li><a href="/comp/msg/casual/list">カジュアル面談</a>：<span>{{ $member_act['user_casual_cnt'] }}件</span></li>
                                <li><a href="/comp/msg/formal/list">正式応募</a>：<span>{{ $member_act['user_formal_cnt'] }}件</span></li>
                                <li><a href="/comp/msg/event/list">イベント</a>：<span>{{ $member_act['event_cnt'] }}件</span></li>
                            </ul>
                        </div><!-- /.adminCharge -->

                    </div><!-- /.adminInformation-sec -->

                </div><!-- /.sideContentsInner -->
            </div><!-- /.sideContents -->
