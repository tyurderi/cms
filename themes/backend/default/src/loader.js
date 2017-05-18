// call this in a directory where you want to load the plugins
export default function loader(requireContext) {
    // contains all the loaded stuff
    const instances = {}

    // get the context keys
    // get their module ids, then require them with __webpack_require__
    const contextKeys = requireContext.keys()
    for (let i = 0, len = contextKeys.length; i < len; i++) {
        // use the file, to get the webpackId, to require the file with webpack
        const file = contextKeys[i]
        const webpackId = requireContext.resolve(file)

        // do whatever you want with the instance
        const instance = __webpack_require__(webpackId)
        instances[file] = instance
    }

    return instances
}