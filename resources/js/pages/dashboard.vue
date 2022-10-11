<template>
<div class="container row">
    <div class="col-md-9 offset-md-3">
        <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
            <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
            <strong>{{error}}</strong>
        </div>
        <div class="container">
            <div class="input-group mb-3 p-2">
                <input type="checkbox" name="showAllTask" id="showAllTask" v-model="showAll" />
                <label for="showAllTask" class="p-2"> Show All Tasks</label>
            </div>
            <div class="input-group mb-3">
                <!-- <button class="btn btn-outline-secondary" type="button">Button</button> -->
                <input type="text" @keyup.enter="addNewTask()" autofocus class="form-control" placeholder="Enter new TODO" v-model="newTask">
                <button class="btn btn-outline-secondary btn-primary text-light" type="button" @click="addNewTask()">Add</button>
            </div>
        </div>
        <div class="container m-3">
            <table class="table table-bordered">
                <tr v-for="(data,index) in allTask" class="p-1">
                    <td class="col-1"><input type="checkbox" class="text-bg-success" :id="'update_'+data.id" @click="update(data.id, null)" v-bind:checked="data.active =='2'" /></td>
                    <td class="col-9"><strong>{{data.description}}</strong></td>
                    <td class="col-2 text-success">{{data.name.toUpperCase()}}</td>
                    <td class="col-1"><i class="fa fa-trash-o" style="font-size:24px;color:red" @click="update(data.id, 0)"></i></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</template>

<script>
import $axios from 'axios';
export default {
    name: 'Dashboard',
    data() {
        return {
            tasks: null,
            newTask: null,
            allTask: null,
            error: null,
            showAll: false,
        }
    },
    beforeCreate() {
        let ref = this;
        if (!window.Laravel.isLogin) {
            // window.location.href = "/login"; 
            ref.$router.push({
                name: 'Login'
            });
        }
    },

    created() {
        let ref = this;
        ref.getTask();

        window.Echo.private("updateTodo").listen("TodoCreatedOrUpdated", e => {
            ref.getTask();
        });
    },
    methods: {
        addNewTask() {
            let ref = this;
            if (ref.newTask == null) {
                ref.error = 'Please add a TODO!';
                // setTimeout(() => {
                //     ref.error = null;
                // }, 7000);
                return
            }
            $axios.post('/api/addTodo', {
                    'task': ref.newTask,
                    'filter': ref.showAll == true ? 'all' : '1'
                })
                .then(r => {
                    if (r.data.status == 200) {
                        ref.newTask = null;
                        ref.allTask = r.data.data;
                    } else {
                        ref.error = r.data.message;
                        setTimeout(() => {
                            ref.error = null;
                        }, 7000);
                    }
                })
        },
        getTask(data1) {
            let ref = this;
            $axios.post('/api/ShowTask', { 'filter': ref.showAll == true ? 'all' : '1' })
                .then(r => {
                    if (r.data.status == 200) {
                        ref.allTask = null;
                        ref.allTask = r.data.data;
                    } else {
                        ref.error = r.data.message;
                        setTimeout(() => {
                            ref.error = null;
                        }, 7000);
                    }
                })
        },
        update(data, val) {
            let ref = this;
            if (val == null) {
                let isChecked = $('body').find('#update_' + data).is(":checked")
                val = isChecked ? 2 : 1;
            }

            $axios.patch('/api/complate', {
                    'id': data,
                    'val': val
                })
                .then(r => {
                    if (r.data.status == 200) {
                        ref.getTask();
                    } else {
                        ref.error = r.data.message;
                        setTimeout(() => {
                            ref.error = null;
                        }, 7000);
                    }
                })
        },
        archive(id) {
            let ref = this;
            console.log(id);
        },
        
    },
    mounted() {
        let ref = this;

        $('body').on('change', '#showAllTask', function (event) {
            event.stopPropagation();
            ref.getTask();
        })

    }

}
</script>
