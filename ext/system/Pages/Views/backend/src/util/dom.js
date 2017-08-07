export default {
    up(element, selector)
    {
        do
        {
            if (this.is(element, selector))
            {
                return element;
            }
        } while((element = element.parentNode) !== null);
    },
    down(element, selector)
    {
        let matches = [];
        
        for (let i = 0; i < element.children.length; i++)
        {
            if (this.is(element.children[i], selector))
            {
                matches.push(element.children[i]);
            }
            
            this.down(element.children[i], selector).forEach(n => matches.push(n));
        }
        
        return matches;
    },
    is(element, selector)
    {
        return element.classList.contains(selector)
            || element.id === selector;
    }
}