<template>
    <div class="config-site-form">
        <div class="head">
            <div class="title">
                <span v-if="isNew">
                    Create site
                </span>
                <span v-else>
                    Edit site
                </span>
            </div>
            <ul class="actions">
                <li>
                    <a href="#">
                        <button class="fa fa-check" form="site"></button>
                    </a>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="form-container" v-if="site">
                <form @submit.prevent="submit" id="site">
                    <div class="form-item" v-if="site.id !== null">
                        <label for="id">
                            ID
                        </label>
                        <input type="text" id="id" :value="site.id" readonly />
                    </div>
                    <div class="form-item">
                        <label>
                            Active
                        </label>
                        <checkbox name="active" v-model="site.active"></checkbox>
                    </div>
                    <div class="form-item">
                        <label for="label">
                            Label
                        </label>
                        <input type="text" id="label" v-model="site.label" />
                    </div>
                    <div class="form-item">
                        <label>
                            SSL
                        </label>
                        <checkbox name="ssl" v-model="site.secure"></checkbox>
                    </div>
                    <div class="form-item">
                        <label for="host">
                            Host
                        </label>
                        <input type="text" id="host" v-model="site.host" />
                    </div>
                    <div class="form-item">
                        <label for="hosts">
                            Hosts
                        </label>
                        <textarea id="hosts" v-model="site.hosts"></textarea>
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
    data: () => ({
        /**
         * This will only be used when the form is in isNew mode
         */
        editingSite: {
            id: null,
            active: true,
            label: '',
            host: '',
            hosts: '',
            secure: false,
            created: null,
            changed: null
        }
    }),
    computed: {
        site()
        {
            if (this.isNew)
            {
                return this.editingSite;
            }

            return this.$store.getters['site/items'].find(site => site.id === this.$route.params.id);
        },
        isNew()
        {
            return this.$route.name === 'config-site-create';
        }
    },
    mounted()
    {
        let siteID = this.$route.params.id;

        this.$progress.start();

        async.series([
            (done) =>
            {
                // Do not load anything in isNew mode
                if (this.isNew === true)
                {
                    done();
                    return;
                }

                // Do not load user when it's already assigned
                if (this.site && this.site.id && siteID === this.site.id)
                {
                    done();
                    return;
                }

                this.$http.post('api/site/get', { id: siteID })
                    .then(
                        response => {
                            if (!response.body.success)
                            {
                                done(true);
                                return;
                            }

                            console.log(response.body.data.secure);

                            this.$store.commit('site/add', [response.body.data]);
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

            this.$http.post('api/site/save', this.site)
                .then(
                    response => {
                        if (!response.body || !response.body.success)
                        {
                            this.$store.dispatch('error/push', response);
                            this.$progress.fail();
                        }
                        else
                        {
                            this.$router.push({ name: 'config-site-edit', params: { id: response.body.id } });
                            this.$progress.finish();

                            this.$toast.push({
                                text: 'The site were saved successfully',
                                type: 'success',
                                delay: 3000
                            })
                        }
                    },
                    response => {
                        this.$progress.fail();
                        this.$store.dispatch('error/push', response);
                    }
                )
        }
    }
}
</script>

<style lang="less" scoped>
.config-site-form {
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