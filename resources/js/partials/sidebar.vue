<template>
	<b-col id="sidebar" xl="2" lg="3" md="3" sm="12" cols="12">
		<b-collapse id="sidebar-collapse"  :visible="sidebarCollapse">

            <a><img class="user-img" src="assets/images/logo.jpg" alt=""></a>

			<h5 class="text-white text-center mt-2">{{$t('website')}}</h5>
			<ul>

				<li @click="activeArrow=0" :class="{'Link':true,'active_link':getUrl=='/'}">
					<router-link to="/">
						<i class="fa fa-users mar-3"></i>
						<span id="menu_name">{{$t('users')}}</span>
					</router-link>
				</li>

                <li @click="activeArrow=1" :class="{'Link':true,'active_link':getUrl=='/sites'}">
                    <router-link to="/sites">
                        <i class="fa fa-users mar-3"></i>
                        <span id="menu_name">{{$t('sites')}}</span>
                    </router-link>
                </li>

                <li @click="activeArrow=2" :class="{'Link':true,'active_link':getUrl=='/news'}">
                    <router-link to="/news">
                        <i class="fa fa-users mar-3"></i>
                        <span id="menu_name">{{$t('news')}}</span>
                    </router-link>
                </li>


                <li @click="activeArrow=4" :class="{'Link':true,'active_link':getUrl=='/packages'}">
                    <router-link to="/packages">
                        <i class="fa fa-users mar-3"></i>
                        <span id="menu_name">{{$t('packages')}}</span>
                    </router-link>
                </li>

                <li @click="activeArrow=3" :class="{'Link':true,'active_link':getUrl=='/about'}">
                    <router-link to="/about">
                        <i class="fa fa-users mar-3"></i>
                        <span id="menu_name">{{$t('about')}}</span>
                    </router-link>
                </li>

                <li @click="activeArrow=3" :class="{'Link':true,'active_link':getUrl=='/complains'}">
                    <router-link to="/complains">
                        <i class="fa fa-users mar-3"></i>
                        <span id="menu_name">{{$t('complains')}}</span>
                    </router-link>
                </li>

                <li @click="activeArrow=3" :class="{'Link':true,'active_link':getUrl=='/banners'}">
                    <router-link to="/banners">
                        <i class="fa fa-flag mar-3"></i>
                        <span id="menu_name">{{$t('banners')}}</span>
                    </router-link>
                </li>

                <li @click="activeArrow=3" :class="{'Link':true,'active_link':getUrl=='/products'}">
                    <router-link to="/products">
                        <i class="fa fa-box mar-3"></i>
                        <span id="menu_name">{{$t('products')}}</span>
                    </router-link>
                </li>

                <li @click="activeArrow=3" :class="{'Link':true,'active_link':getUrl=='/podcasts'}">
                    <router-link to="/podcasts">
                        <i class="fa fa-box mar-3"></i>
                        <span id="menu_name">{{$t('podcasts')}}</span>
                    </router-link>
                </li>


                <!-- users
				<li :class="{'Link':true,'active_link':$route.path.indexOf('/admin/users')==0}">

					<a href="#" @click="setActiveArrow(1)"  class="has-ul" v-b-toggle.collapse-1>
						<i class="fa fa-users mar-3"></i>
						<span id="menu_name">
						     Users
						</span>
						<i :class="{'fas':true,'arrow':true,[getClass]:true,'mt-1':true}"></i>
					</a>

					<b-collapse  accordion="my-accordion" id="collapse-1"  :visible="$route.path.indexOf('/admin/users') === 0">
						<ul :class="{'collapseable':true}">

						   <li  :class="[getUrl=='/admin/users/admins' ? 'active' : '']">
							  <router-link to="/admin/users/admins">
							    <span>
							    	Admins
								</span>
							  </router-link>
						   </li>

							<li :class="[getUrl=='/admin/users/clients' ? 'active' : '']">
								<router-link to="/admin/users/clients">
							    <span>
							    	Clients
								</span>
								</router-link>
							</li>

						</ul>
					</b-collapse>
				</li>
				 -->


                <li @click="activeArrow=0" class="Link">
					<a @click="logout()">
						<i class="fas fa-power-off"></i>
						<span id="menu_name">{{$t('logout')}}</span>
					</a>
				</li>


			</ul>
		</b-collapse>
	</b-col>

</template>

<script>
    import messages from '../mixins/messages';
	export default {
       mixins:[messages],
       data() {
       		return {
                user_name:'',
       			activeArrow:0,
                imagePath:'',
       		};
       },
       computed : {
		  	getUrl() {
				return this.$route.path;
			},
			sidebarCollapse() {
				return this.$store.getters.getSidebarCollapse
			},
            getClass() {
                if(this.$i18n.locale=='en') {
                    return 'fa-chevron-right';
                }
                return 'fa-chevron-left';
            },
       },
       methods: {
			setActiveArrow(num) {
				if(this.activeArrow==num) {
					this.activeArrow=0;
				}
				else {
					this.activeArrow=num;
				}
			},
           logout() {
               this.$store.dispatch('logout')
                   .then(response=> {
                       this.$router.push('/login');
                   })
           },
       },
		created() {
			if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
				this.$store.dispatch('hideSlidebar');
			}
        },
        mounted() {
            window.onresize = () => {
                if(window.innerWidth>767) {
                    this.$store.dispatch('showSlidebar');
                }
                else {
                    this.$store.dispatch('hideSlidebar');
                }
            };
        }
	}
</script>


<style>

    #sidebar{
        background-color: #222;
        padding: 0px;
        position: relative;
        width: 100%;
        margin:0;
    }

    #sidebar h1{
        color: #fff;
        text-align: center;
        margin: 10px 0px;
        font-size: 25px;
    }

    #sidebar ul{
        list-style: none;
        margin: 0;
        padding: 0;
    }

    #sidebar i{
        color:#fff;
        font-size: 20px;
        top: 1px;
    }

    #sidebar img {
        width: 20px;
        height: 20px;
    }

    #sidebar #menu_name{
        position: relative;
        text-transform: capitalize;
    }

    #right #sidebar #menu_name{
        right: 8px;
    }

    #left #sidebar #menu_name{
        left: 8px;
    }

    #sidebar .Link:hover > a{
        background-color:#1ab394;
        display: block;
        color: #fff;
    }

    #sidebar a{
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        color: #9fb1c2;
        font-weight: bold;
        font-size: 14px;
        display:block;
        position: relative;
    }

    #right .arrow::before {
        content: "\f053";
    }

    #right .arrow {
        float: left;
    }


    #left .arrow:before {
        content: "\f054";
    }

    #left .arrow {
        float: right;
    }

    .collapseable {
        background: #333;
        display: block !important;
    }

    #right ul.collapseable {
        border-right:6px solid #1ab394;
    }

    #left ul.collapseable {
        border-left:6px solid #1ab394;
    }


    #right .collapseable li {
        padding-right: 8px;
        padding-top: 5px;
    }


    #left .collapseable li {
        padding-left: 8px;
        padding-top: 5px;
    }


    .collapseable li:hover {
        background-color:#111;
    }


    .collapseable li:hover span{
        color:#fff;
    }


    .collapseable > li.active {
        background-color:#111;
    }


    .collapseable > li.active span{
        color:#fff;
    }

    .active_link {
        background: #1ab394;
    }


    .active_link2 {
        background: #1ab394;
    }

    .active_link  span {
        color: #fff;
    }

    .active_link2  span {
        color: #fff;
    }

    .arrow {
        transition: .5s;
    }

    #right .active-arrow {
        transform: rotate(-90deg);
    }

    #left .active-arrow {
        transform: rotate(90deg);
    }

    @media (min-width: 767px) {
        #sidebar h1{
            margin-top: 20px;
        }

        #sidebar {
            min-height: 100vh;
        }
    }


    .user-img {
        position:relative;
        top:10px;
        width:100px !important;
        height:100px !important;
        /*border-radius:50% !important;*/
    }

    #left .user-img {
        left:50%;
        transform:translateX(-50%);
    }

    #right .user-img {
        right:50%;
        transform:translateX(50%);
    }

    #right .arrow::before{content:"\f053"}

    #right .arrow{float:left}

    #left .arrow:before{content:"\f054"}#left

    .arrow{float:right}

    .active_link .svg-inline--fa {
        color: white !important;
    }

    .Link .fa {
        color: white  !important;
    }

</style>


