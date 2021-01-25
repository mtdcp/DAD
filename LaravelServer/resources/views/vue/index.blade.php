@extends('master')

@section('title', 'Virtual Wallet')

@section('content')

<router-link to="/login" v-if="!$store.state.user && $route.path!='/privateInfoPage' && $route.path!='/login'"><button class="btn btn-outline-primary">Login</button></router-link>

<router-link to="/myProfile" v-if="$store.state.user"><button class="btn btn-outline-success">My Profile</button></router-link>

<router-link to="/statistics" v-if="$store.state.user && $store.state.user.type == 'Administrator'"><button class="btn btn-outline-primary">Statistics</button></router-link>

<router-link to="/usersStatistics" v-if="$store.state.user && $store.state.user.type == 'User'"><button class="btn btn-outline-primary">Statistics</button></router-link>

<router-link to="/users" v-if="$store.state.user && $store.state.user.type == 'Administrator'"><button class="btn btn-outline-primary">Users</button></router-link>

<router-link to="/movements" v-if="$store.state.user && $store.state.user.type == 'User'"><button class="btn btn-outline-primary">Movements</button></router-link>

<router-link to="/movementRegister" v-if="$store.state.user && $store.state.user.type == 'Operator'"><button class="btn btn-outline-primary">Create Movement</button></router-link>

<router-link to="/logout" v-if="$store.state.user"><button class="btn btn-outline-danger">Logout</button></router-link>

<router-link to="/createAccount" v-if="!$store.state.user && $route.path!='/privateInfoPage' && $route.path!='/createAccount'"><button class="btn btn-outline-success">Create Account</button></router-link>

<br><br><br>
<router-view><router-view>

@endsection
@section('pagescript')
<script src="js/vue.js"></script>
 @stop  
