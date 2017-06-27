import Store from '@/store';

export default {
    install(Vue)
    {
        Vue.prototype.$toast = {
            push(item)
            {
                let message = {
                    type: item.type || 'default',
                    text: item.text || '',
                    hidden: false,
                    hideProgress: null
                };

                Store.commit('PUSH_TOAST', message);

                if (item.delay && item.delay > 0)
                {
                    let timeout = item.delay -= 500;

                    message.hideProgress = (item.delay / timeout * 100);

                    let interval = setInterval(() => {
                        item.delay -= 500;

                        message.hideProgress = (item.delay / timeout * 100);

                        if (item.delay <= -500)
                        {
                            message.hidden = true;
                            item.delay = 0;

                            clearInterval(interval);
                        }
                    }, 500);
                }
            }
        };
    }
}