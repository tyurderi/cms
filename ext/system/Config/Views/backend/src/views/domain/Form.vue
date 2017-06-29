<template>
    <div class="config-domain-form">
        <div class="head">
            <div class="title">
                <span v-if="isNew">
                    Create domain
                </span>
                <span v-else>
                    Edit domain
                </span>
            </div>
            <ul class="actions">
                <li>
                    <a href="#">
                        <button class="fa fa-check" form="domain"></button>
                    </a>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="form-container" v-if="domain">
                <form @submit.prevent="submit" id="domain">
                    <div class="form-item" v-if="domain.id !== null">
                        <label for="id">
                            ID
                        </label>
                        <input type="text" id="id" :value="domain.id" readonly />
                    </div>
                    <div class="form-item">
                        <label>
                            Active
                        </label>
                        <checkbox name="active" v-model="domain.active"></checkbox>
                    </div>
                    <div class="form-item">
                        <label for="label">
                            Label
                        </label>
                        <input type="text" id="label" v-model="domain.label" />
                    </div>
                    <div class="form-item">
                        <label>
                            SSL
                        </label>
                        <checkbox name="ssl" v-model="domain.secure"></checkbox>
                    </div>
                    <div class="form-item">
                        <label for="host">
                            Host
                        </label>
                        <input type="text" id="host" v-model="domain.host" />
                    </div>
                    <div class="form-item">
                        <label for="hosts">
                            Hosts
                        </label>
                        <textarea id="hosts" v-model="domain.hosts"></textarea>
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
        editingDomain: {
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
        domain()
        {
            if (this.isNew)
            {
                return this.editingDomain;
            }

            return this.$store.getters['domain/items'].find(domain => domain.id === this.$route.params.id);
        },
        isNew()
        {
            return this.$route.name === 'config-domain-create';
        }
    },
    mounted()
    {
        let domainID = this.$route.params.id;

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
                if (this.domain && this.domain.id && domainID === this.domain.id)
                {
                    done();
                    return;
                }

                this.$http.post('api/domain/get', { id: domainID })
                    .then(
                        response => {
                            if (!response.body.success)
                            {
                                done(true);
                                return;
                            }

                            this.$store.commit('domain/add', [response.body.data]);
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

            this.$http.post('api/domain/save', this.domain)
                .then(
                    response => {
                        if (!response.body || !response.body.success)
                        {
                            this.$store.dispatch('error/push', response);
                            this.$progress.fail();
                        }
                        else
                        {
                            this.$router.push({ name: 'config-domain-edit', params: { id: response.body.id } });
                            this.$progress.finish();

                            this.$toast.push({
                                text: 'The domain were saved successfully',
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
.config-domain-form {
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