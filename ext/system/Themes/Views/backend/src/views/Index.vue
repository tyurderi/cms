<template>
    <div class="themes">
        <div class="head">
            <div class="title">
                Themes
            </div>
            <ul class="actions">
                <li>
                    <a href="#" @click.prevent="load">
                        <i class="fa fa-refresh"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="theme row" v-for="item in items">
                <div class="theme-preview">
                    <img :src="item.preview"  @error="item.imageOk = false" v-if="item.imageOk" />
                    <div class="center full-height align-center" v-else>
                        <span>No image</span>
                    </div>
                </div>
                <div class="theme-meta flex column">
                    <div class="theme-name">
                        <small>{{ item.module }}</small>
                        {{ item.label }}
                    </div>
                    <div class="theme-description">
                        {{ item.description }}
                    </div>
                </div>
                <div class="theme-actions">
                    <!--<button @click="compile(item)">
                        <i class="fa fa-arrow-circle-right"></i>
                    </button>-->
                    <v-loader @click="compile($event, item)"></v-loader>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VLoader from '@Themes/modules/Loader';
export default {
    name: 'themes',
    data: () => ({
        items: []
    }),
    mounted()
    {
        this.load();
    },
    methods: {
        load()
        {
            this.$http.get('api/theme/list')
                .then((response) => {
                    this.items = response.body.data;
                })
        },
        compile(e, { id, module, name })
        {
            e.setBusy();
            e.prepare();
            
            this.$http.get('api/theme/getStatistics', { params: { id }})
                .then((response) => {
                    let promise = this.executeCompile({ module, name })
                        .then(() => {
                            promise.done = true;
                            e.finish();
                        });
                    
                    if (response.body.compiles === 0)
                    {
                        e.delay();
                    }
                    else
                    {
                        e.compile(response.body.duration.avg)
                            .then(() => {
                                e.delay();
                            });
                    }
                });
        },
        executeCompile({ module, name })
        {
            return new Promise((accept, reject) => {
                this.$http.get('api/theme/compile', { params: { module, name } })
                    .then((response) => {
                        accept(response.body);
                    });
            })
        }
    },
    components: {
        VLoader
    }
}
</script>

<style lang="less">
.themes {
    .theme {
        padding: 15px;
        background: #fff;
        margin: 0 0 10px 0;
        .theme-preview {
            width: 100px;
            height: 100px;
            position: relative;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.1);
            margin: 0 15px 0 0;
            img {
                position: absolute;
                width: 100%;
                top: 50%;
                -ms-transform: translateY(-50%);
                -webkit-transform: translateY(-50%);
                transform: translateY(-50%);
            }
        }
        .theme-meta {
            .theme-name {
                font-size: 20px;
                font-weight: 100;
            }
            .theme-description {
                font-size: 14px;
            }
        }
        .theme-actions {
        
        }
    }
}
</style>