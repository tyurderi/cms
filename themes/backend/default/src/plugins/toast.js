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

                if (item.timeout && item.timeout > 0)
                {
                    let timeout = item.timeout;

                    let interval = setInterval(() => {
                        item.timeout -= 500;

                        message.hideProgress = (item.timeout / timeout * 100);

                        if (item.timeout <= -500)
                        {
                            message.hidden = true;
                            item.timeout = 0;

                            clearInterval(interval);
                        }
                    }, 500);
                }
            }
        };
    }
}