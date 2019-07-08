<!--ナビゲーションタブの部分の切り出し-->
<div id="app">
<ul class="nav nav-tabs nav-justified mb-3">
    <li v-on:click="change('1')" v-bind:class="{'active': isActive === '1'}" class="nav-item">
        <a href="{{ route('users.show', ['id' => $user->id]) }}" class="nav-link {{ Request::is('users/' . $user->id) ? 'active' : '' }}">TimeLine <span v-if="isActive === '1'" class="badge badge-secondary">{{ $count_microposts }}</span></a>
    </li>
    <li v-on:click="change('2')" v-bind:class="{'active': isActive === '2'}" class="nav-item">
        <a href="{{ route('users.followings', ['id' => $user->id]) }}" class="nav-link {{ Request::is('users/*/followings') ? 'active' : '' }}">Followings <span v-else-if="isActive === '2'" class="badge badge-secondary">{{ $count_followings }}</span></a>
    </li>
    <li class="nav-item">
        <a href="{{ route('users.followers', ['id' => $user->id]) }}" class="nav-link {{ Request::is('users/*/followers') ? 'active' : '' }}">Followers <span class="badge badge-secondary">{{ $count_followers }}</span></a>
    </li>
    <li class="nav-item">
        <a href="{{ route('users.favorites', ['id' => $user->id]) }}" class="nav-link {{ Request::is('users/*/favorites') ? 'active' : '' }}">Favorites <span class="badge badge-secondary">{{ $count_favorites }}</span></a>
    </li>
</ul>
</div>

<script>
    new Vue({
  el: '#app',
  data: {
    isActive: '1'
  },
  methods: {
    change: function(num){
      this.isActive = num
    }
  }
})
</script>