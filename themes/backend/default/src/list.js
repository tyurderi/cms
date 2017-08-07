export default class List
{
    constructor(items)
    {
        this.replace(items);
    }
    replace(items)
    {
        this.items = [];
        items.forEach(n => this.push(n));
    }
    push(item)
    {
        item.next = () => { return this.get(this.index(item) + 1); };
        item.prev = () => { return this.get(this.index(item) - 1); };

        this.items.push(item);
    }
    remove(item)
    {
        this.items.splice(
            this.index(item),
            1
        );
    }
    get first()
    {
        return this.get(0);
    }
    get last()
    {
        return this.get(this.length() - 1);
    }
    get length()
    {
        return this.items.length;
    }
    get(index)
    {
        return this.items[index] || null;
    }
    set(index, item)
    {
        this.items[index] = item;
    }
    index(item)
    {
        return this.items.indexOf(item);
    }
    get all()
    {
        return this.items;
    }
    // several array methods
    find(cb)      { return this.items.find(cb) }
    findIndex(cb) { return this.items.findIndex(cb) }
    filter(cb)    { return this.items.filter(cb) }
    splice(start, index) { return this.items.splice(start, index); }
    indexOf(item) { return this.items.indexOf(item); }
}