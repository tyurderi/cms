<template>
    <div class="theme-loader">
        <div class="loader">
            <svg viewBox="-25 -25 50 50" style="transform: rotate(-90deg)">
                <circle r="20" stroke-width="4" stroke="#ccc" fill="transparent" stroke-linecap="round"
                        ref="baseCircle"></circle>
                
                <transition name="fade">
                    <circle r="20" stroke-width="2" stroke="#e74c3c" fill="transparent" stroke-linecap="round"
                            class="spin" v-show="loading" ref="spinCircle"></circle>
                </transition>
                <transition name="fade">
                    <circle r="20" stroke-width="2" stroke="#e74c3c" fill="transparent" stroke-linecap="round"
                            class="progress" v-show="progress!==null" ref="progressCircle"></circle>
                </transition>
            </svg>
            <div class="loader-content">
                <i class="fa fa-play-circle-o fa-2x" @click="start" v-if="!busy"></i>
                <span v-if="busy&&text" v-html="text"></span>
            </div>
        </div>
    </div>
</template>

<script>
import { TweenLite, Power0 } from 'gsap';

export default {
    name: 'theme-loader',
    data: () => ({
        loading: false,
        progress: null,
        busy: false,
        text: null
    }),
    methods: {
        /**
         * Method to set the percentage of a svg circle.
         * @param el
         * @param value
         */
        updateProgress(el, value)
        {
            let circumFerence = 2 * Math.PI * parseInt(el.getAttribute('r')),
                progress = value / 100,
                dashOffset = circumFerence * (1 - progress);
            
            el.style.strokeDasharray = circumFerence;
            el.style.strokeDashoffset = dashOffset;
        },
        start()
        {
            this.killTween();
            this.$emit('click', this);
        },
        setBusy()
        {
            this.busy = true;
            this.$refs.baseCircle.style.stroke = '#333';
        },
        prepare()
        {
            this.loading  = true;
            this.progress = null;
            
            this.$refs.baseCircle.style.stroke     = '#333';
            this.$refs.progressCircle.style.stroke = '#e74c3c';
            this.$refs.spinCircle.style.stroke     = '#e74c3c';
        },
        compile(time)
        {
            return new Promise((done) => {
                this.loading  = false;
                this.progress = 0;
                this.tween    = TweenLite.to(this, time, {
                    progress: 100,
                    ease: Power0.easeNone,
                    onUpdate: () => {
                        this.updateProgress(this.$refs.progressCircle, this.progress);
                    },
                    onComplete: done
                });
            });
        },
        delay()
        {
            this.progress = null;
            this.loading  = true;
            this.$refs.spinCircle.style.stroke = '#f1c40f';
        },
        finish()
        {
            this.killTween();
            
            this.progress = 100;
            this.$refs.progressCircle.style.stroke = '#2ecc71';
            this.updateProgress(this.$refs.progressCircle, this.progress);
            
            this.loading = false;
            this.busy    = false;
        },
        killTween()
        {
            if (this.tween)
            {
                this.tween.kill();
            }
        }
    }
}
</script>

<style lang="less" scoped>
.theme-loader {
    circle {
        transition: stroke .25s;
        &.spin {
            animation: dash 1.5s ease-in-out infinite, spin 2s linear infinite;
        }
    }
    .loader {
        position: relative;
        width: 100px;
        height: 100px;
        svg {
            position: relative;
            z-index: 0;
        }
        .loader-content {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 5;
            i {
                cursor: pointer;
                color: #333;
            }
            span {
                font-size: 20px;
                font-family: 'Consolas', monospace;
            }
        }
    }
}
@keyframes spin {
    from {
        transform:rotate(0deg);
    }
    to {
        transform:rotate(360deg);
    }
}
@keyframes dash {
    0% {
        stroke-dasharray: 1, 150;
        stroke-dashoffset: 0;
    }
    50% {
        stroke-dasharray: 90, 150;
        stroke-dashoffset: -35;
    }
    100% {
        stroke-dasharray: 90, 150;
        stroke-dashoffset: -124;
    }
}
.fade-enter-active, .fade-leave-active {
    transition: opacity .25s
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0
}
</style>