<template>
    <b-container fluid class="contain bg-white" :id="getLocale">
        <b-row>
            <admin-navbar></admin-navbar>
        </b-row>

        <b-row>
            <b-col xl="10" lg="9" md="9" sm="12" cols="12" class="overflow-x:auto order-sm-2 order-md-2 order-2"
                id="content">
                <b-container class="mt-5">

                    <b-row class="mb-4">

                        <b-col lg="4" class="mx-2">
                            <b-input-group size="sm">
                                <b-form-input v-model="searchText" type="search" id="filterInput"
                                    :placeholder="$t('search')" v-on:keyup.enter="
                                        search((currentPage = 1));
                                    isBusy = true;
                                    "></b-form-input>
                            </b-input-group>
                        </b-col>


                        <b-col class="col-2">
                            <b-form-select class="material-select " v-model="isRead">
                                <option value="-1">{{ $t('all') }}</option>
                                <option value="0">{{ $t('UnRead') }}</option>
                                <option value="1">{{ $t('Read') }}</option>
                            </b-form-select>
                        </b-col>



                        <div class="col-2">
                            <div class="form-input">
                                <button class="btn btn-sm btn-success " @click="get(1)">{{ $t('search')
                                }}</button>
                            </div>
                        </div>


                    </b-row>


                    <!-- Main table element -->
                    <b-table show-empty small stacked="md" class="mt-3 rtl" :items="complains" :fields="fields"
                        :busy="isBusy" bordered>
                        <template v-slot:cell(number)="row">
                            {{ parseFloat(row.index + 1 + (currentPage - 1) * 25) }}
                        </template>

                        <template v-slot:cell(action)="row">
                            <b-button variant="primary" size="sm" @click="edit(row.item, row.index)"><i
                                    class="fa fa-eye"></i></b-button>

                            <b-button class="icon-badge-container" variant="primary" size="sm"
                                @click="showMessageModal(row.item)">
                                <i class="fa fa-envelope icon-badge-icon"></i>
                                <div v-if="row.item.replies.length >= 1" variant="danger" class="icon-badge">{{ row.item.replies.length }}</div>
                            </b-button>

                            <b-button v-if="row.item.isRead === 1" variant="success" size="sm"><i
                                    class="fa fa-check"></i></b-button>
                            <b-button v-if="row.item.isRead === 0" variant="warning" size="sm" @click="read(row.item)"><i
                                    class="fa fa-check"></i></b-button>
                        </template>


                        <template v-slot:table-busy>
                            <div class="text-center text-danger my-2">
                                <b-spinner class="align-middle"></b-spinner>
                                <strong>Loading...</strong>
                            </div>
                        </template>
                    </b-table>

                    <b-col lg="3" class="my-1">
                        <b-pagination v-model="currentPage" @change="
                            get((currentPage = $event));
                        isBusy = true;
                        " :total-rows="totalRows" :per-page="perPage" align="fill" size="sm"
                            class="my-0"></b-pagination>
                    </b-col>

                    <!-- Info modal -->
                    <b-modal id="infoModal" :content-class="{
                        right: $i18n.locale == 'en' ? false : true,
                        left: $i18n.locale == 'en' ? true : false,
                    }" :title="$t('complains')" @hide="clear">
                        <p>{{ complain.username }} - {{ complain.phone }}</p>
                        <p>{{ complain.desc }}</p>
                    </b-modal>

                    <!-- Message Modal -->
                    <b-modal id="messageModal" :content-class="'custom-message-modal'" title="Send Message"
                        v-model="messageModalVisible" @hide="hideMessageModal">
                        <div>
                            <!-- Displaying Message History -->
                            <div class="message-history">
                                <div v-for="reply in selectedComplainReplies" :key="reply.id" class="message">
                                    <div class="message-card"
                                        v-bind:class="'message-data ' + (reply.isAdmin == 0 ? 'float-right message-user ' : 'float-left message-admin ')">
                                        <!-- Avatar -->
                                        <div class="avatar-container">
                                            <i v-if="reply.isAdmin == 0" class="avatar-user fas fa-user text-xl"></i>
                                            <i v-else class="avatar-admin fas fa-user-secret text-xl"></i>
                                        </div>

                                        <!-- Message Content -->
                                        <div class="message-content"
                                            v-bind:class="'message-data ' + (reply.isAdmin == 0 ? 'float-right content-user ' : 'float-left content-admin ')">
                                            <h6>{{ reply.title }}</h6>
                                            <p class="message-body">{{ reply.body.replace(/\n/g, ' ') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <b-form-textarea v-model="messageContent" placeholder="Type your message here..."
                                rows="3"></b-form-textarea>
                        </div>
                        <div class="text-right mt-3">
                            <b-button variant="primary" @click="sendMessage">Send</b-button>
                            <b-button variant="secondary" @click="fetchReplies">Fetch Replies</b-button>
                        </div>
                    </b-modal>

                </b-container>
            </b-col>
            <sidebar></sidebar>
        </b-row>
    </b-container>
</template>

<script>
import messages from "../mixins/messages";

export default {
    mixins: [messages],
    name: "complain",
    data() {
        return {
            isRead: 0,
            complains: [],
            complain: {
                id: "",
                username: "",
                desc: "",
                phone: "",
                created_at: "",
                isRead: 0,
                replies: [],
            },
            messageModalVisible: false,
            messageContent: "",
            selectedComplainReplies: [],
            selectedComplain: null,
            errors: [],
            filter: null,
            filterOn: [],
            totalRows: 1,
            currentPage: 1,
            perPage: 25,
            sortBy: "",
            search: "",
            isBusy: true,
        };
    },
    methods: {
        showMessageModal(complain) {
            this.selectedComplain = complain;
            this.messageModalVisible = true;
            this.selectedComplainReplies = complain.replies;
            th.fetchReplies();
        },
        hideMessageModal() {
            this.messageContent = "";
            this.selectedComplain = null;
            this.messageModalVisible = false;
        },
        edit(item, index) {
            this.complain.id = item.id;
            this.complain.username = item.username;
            this.complain.phone = item.phone;
            this.complain.desc = item.desc;
            this.$root.$emit("bv::show::modal", "infoModal");
        },
        read(item) {
            axios
                .get("/api/complains/read/" + item.id)
                .then((response) => {
                    this.get();
                    this.makeToast("success", this.$t("read"));
                })
                .catch((error) => {
                    this.makeToast("danger", this.$t("error_occured"));
                });
        },
        get(page) {
            this.isBusy = true;
            axios
                .get("/api/complains/get?page=" + page + "&search=" + this.search + "&isRead=" + this.isRead)
                .then((response) => {
                    this.totalRows = response.data.total;
                    this.complains = response.data.data;
                    this.isBusy = false;
                })
                .catch((error) => {
                    this.makeToast("danger", this.$t("error_occured"));
                });
        },
        clear() {
            this.complain.id = "";
            this.complain.username = "";
            this.complain.phone = "";
            this.pakage.desc = "";
            this.errors = [];
        },
        sendMessage() {
            if (!this.selectedComplain || !this.messageContent) {
                return;
            }
            const payload = {
                title: "Online Company",
                body: this.messageContent,
                id: this.selectedComplain.id,
                token_id: this.selectedComplain.token_id,
            };
            var formdata = new FormData();
            formdata.append("complain_id", this.selectedComplain.id);
            formdata.append("title", "Support Team");
            formdata.append("isAdmin", 1);
            formdata.append("body", this.messageContent);

            // Make an axios POST request to send the notification
            axios
                .post("/api/complains/addReplies", formdata)
                .then((response) => {
                    this.makeToast("success", "Replay add");
                })
                .catch((error) => {
                    this.makeToast("danger", "Error sending Replay.");
                });
            axios
                .post("/api/send-notification", payload)
                .then((response) => {
                    this.makeToast("success", "Notification sent successfully.");
                    this.fetchReplies();
                })
                .catch((error) => {
                    this.makeToast("danger", "Error sending notification.");
                });
        },
        fetchReplies() {
            if (!this.selectedComplain) {
                return;
            }
            const complainId = this.selectedComplain.id;

            axios.get(`/api/complains/${complainId}/replies`)
                .then(response => {
                    const replies = response.data.replies;
                    this.selectedComplainReplies = replies;
                })
                .catch(error => {
                    console.error('Error fetching replies:', error);
                });
        }

    },
    computed: {
        fields() {
            return [
                {
                    key: "number",
                    label: "",
                    sortable: true,
                    sortDirection: "asc",
                },
                {
                    key: "username",
                    label: this.$t("username"),
                    sortable: true,
                    sortDirection: "asc",
                },
                { key: "phone", label: this.$t("phone"), sortable: true },
                { key: "created_at", label: this.$t("created_at"), sortable: true },
                { key: "action", label: "#" },
            ];
        },
        getLocale() {
            if (this.$i18n.locale == "en") {
                return "left";
            }
            return "right";
        },
        getUrl() {
            return this.$route.path;
        },
    },
    mounted() {
        this.get(1);
    },
};
</script>


<style scoped>
.custom-message-modal {
    max-width: 400px;
    margin: 0 auto;
    background-color: bisque !important;
}

.message-history {
    display: flex;
    flex-direction: column;
}

.message-card {
    width: 400px;
    padding: 10px;
    height: max-content;
    margin-bottom: 10px;
    height: auto;
    overflow: hidden;
}

.message-content {
    width: 400px;
    padding: 10px;
    height: max-content;
    margin-bottom: 10px;
    height: auto;
    overflow: hidden;
}


.message-user {
    text-align: right;
}

.message-admin {
    text-align: left;
}

.content-user {
    background-color: lightcoral;

}

.content-admin {
    background-color: aquamarine;
}

.message-body {
    flex-wrap: wrap;
    white-space: pre-line;
}

/* // form input css */


.form-input input {
    padding: 10px 0;
    margin-bottom: 30px;
    transition: 0.5s;
}

.form-input textarea {
    height: 80px;
    padding: 10px 0;
    margin-bottom: 40px;
}

.form-input input,
.form-input textarea {
    width: 100%;
    box-shadow: none;
    outline: none;
    border: none;
    box-sizing: border-box;
    border-bottom: 2px solid #999;
    background-color: transparent;
}

.form-input textarea {
    margin-bottom: 20px;
}

.form-input {
    width: 100%;
    position: relative;
}

.form-input label {
    position: absolute;
    top: 10px;
    color: #999;
    transition: 0.5s;
}

#left .form-input label,
.left .form-input label {
    left: 0px !important;
}

#right .form-input label,
.right .form-input label {
    right: 0px !important;
}

.form-input input:focus~label,
.form-input textarea:focus~label,
.form-input input:valid~label,
.form-input textarea:valid~label {
    top: -12px;
    color: #3498db;
    font-size: 12px;
    font-weight: bold;
}

#left .form-input input:focus~label,
#left .form-input textarea:focus~label,
#left .form-input input:valid~label,
#left .form-input textarea:valid~label,
.left .form-input input:focus~label,
.left .form-input textarea:focus~label,
.left .form-input input:valid~label,
.left .form-input textarea:valid~label {
    left: 0px;
}

#right .form-input input:focus~label,
#right .form-input textarea:focus~label,
#right .form-input input:valid~label,
#right .form-input textarea:valid~label,
.right .form-input input:focus~label,
.right .form-input textarea:focus~label,
.right .form-input input:valid~label,
.right .form-input textarea:valid~label {
    right: 0px;
}

.form-input input:focus,
.form-input textarea:focus,
.form-input input:valid,
.form-input textarea:valid {
    border-bottom: 2px solid #3498db;
    transform-origin: left;
}

.form-input .error {
    position: relative;
    top: -25px;
}

#left .form-input .error,
.left .form-input .error {
    left: 0px;
}

#right .form-input .error,
.right .form-input .error {
    right: 0px;
}

.material-select {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
    outline: none !important;
    appearance: none !important;
    width: 100%;

    color: #444 !important;
    border: none !important;
    outline: none !important;
    border-bottom: 2px solid #999 !important;
    margin-top: 0px;
    padding: 5px 5px;
    padding-right: 1.5rem;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
}

.h-45 {
    height: 45px !important;
}

#label-file {
    position: relative;
    color: #666;
    border-bottom: 2px solid #999 !important;
    font-size: 16px;
    font-weight: 400;
    width: 100%;
    margin-top: 15px;
    margin-bottom: 20px;
}

#label-file i {
    color: #666;
    font-size: 22px;
}

#label-file input {
    opacity: 0;
    display: none;
}

.file_input>span {
    position: relative;
    top: -12px;
}


.icon-badge-group .icon-badge-container {
    display: inline-block;
    margin-left: 15px;
}

.icon-badge-group .icon-badge-container:first-child {
    margin-left: 0
}

.icon-badge-container {
    position: relative;
}

.icon-badge-icon {
    position: relative;
}

.icon-badge {
    background-color: red;
    padding: 5px;
    font-size: x-small;
    color: white;
    text-align: center;
    border-radius: 35%;
    height: 80%;
    position: absolute;
    /* changed */
    top: -5px;
    /* changed */
    right: -5px;
    /* changed */
}
</style>
