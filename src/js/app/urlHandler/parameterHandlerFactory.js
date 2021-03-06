import ProductStreamParameters from './productStreamParameters'

/**
 * @param {Object[]} streamConfigurations
 * @param {boolean} readOnly
 * @constructor
 */
const ParameterHandlerFactory = function (streamConfigurations, readOnly = true) {
    this.streamConfigurations = streamConfigurations
    this.readOnly = readOnly

    /**
     * @param {string} streamId
     * @param {Object<string, *>} parameters
     * @return {ProductStreamParameters}
     */
    this.createParameterHandler = (streamId, parameters) => {
        return new ProductStreamParameters(
            (parameters.s || {})[streamId] || {},
            this.readOnly
        )
    }

    /**
     * @param {Object<string, *>} parameters
     * @return {Object<string, ProductStreamParameters>}
     */
    this.createParameterHandlerMap = (parameters) => {
        let streamConfigurations = {}
        for (let streamConfiguration of this.streamConfigurations) {
            streamConfigurations[streamConfiguration.streamId] = this.createParameterHandler(streamConfiguration.streamId, parameters)
        }

        return streamConfigurations
    }
}

export default ParameterHandlerFactory
