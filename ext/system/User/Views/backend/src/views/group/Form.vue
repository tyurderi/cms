<template>
    <div class="group-form">
        <div class="head">
            <div class="title">
                <span v-if="$route.params.id === 'new'">
                    Create group
                </span>
                <span v-else>
                    Edit group
                </span>
            </div>
            <ul class="actions">
                <li>
                    <a href="#">
                        <button class="fa fa-check" form="group"></button>
                    </a>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="form-container" v-if="group">
                <form @submit.prevent="submit" id="group">
                    <div class="form-item" v-if="group.id !== 'new'">
                        <label for="id">
                            ID
                        </label>
                        <input type="text" id="id" :value="group.id" readonly />
                    </div>
                    <div class="form-item">
                        <label for="label">
                            Label
                        </label>
                        <input type="text" id="label" v-model="group.label" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import async from 'async';

    export default {
        computed: {
            group() {
                return this.$store.getters['group/items'].find(group => group.id === this.$route.params.id)
            }
        },
        mounted()
        {
            let groupID = this.$route.params.id;

            this.$progress.start();

            async.series([
                (done) =>
                {
                    if (this.$route.params.id === 'new')
                    {
                        this.$store.commit('group/add', [
                            {
                                id: 'new',
                                label: ''
                            }
                        ]);

                        done();
                        return;
                    }

                    // Do not load user when it's already assigned
                    if (this.group && this.group.id && groupID === this.group.id)
                    {
                        done();
                        return;
                    }

                    this.$http.post('api/group/get', { id: groupID })
                        .then(
                            response => {
                                if (!response.body.success)
                                {
                                    done(true);
                                    return;
                                }

                                this.$store.commit('group/add', [response.body.data]);
                                done();
                            },
                            response => {
                                this.$store.dispatch('error/push', response);
                                done(true);
                            }
                        );
                }
            ], (error, results) => {
                if (error)
                {
                    this.$progress.fail();
                }
                else
                {
                    this.$progress.finish();
                }
            });
        },
        methods: {
            submit()
            {
                this.$progress.start();

                this.$http.post('api/group/save', this.group)
                    .then(
                        response =>
                        {
                            if (!response.body || !response.body.success)
                            {
                                this.$store.dispatch('error/push', response);
                                this.$progress.fail();
                            }
                            else
                            {
                                this.$router.push({ name: 'users-groups-edit', params: { id: response.body.id } });
                                this.$progress.finish();
                            }
                        },
                        response =>
                        {
                            this.$progress.fail();
                            this.$store.dispatch('error/push', response);
                        }
                    )
            }
        }
    }
</script>

<style lang="less" scoped>
    .group-form {
        position: relative;
        height: 100%;
        .form-container {
            padding: 10px;
            background: #fff;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
            .form-item {
                margin: 0 0 10px 0;
                display: flex;
                flex-direction: row;
                &:last-child {
                    margin: 0;
                }
                label {
                    width: 100px;
                    display: inline-block;
                }
                select, textarea, input {
                    flex: 1;
                }
            }
        }
    }
</style>