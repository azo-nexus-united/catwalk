//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'
import Markdown from '../../component/markdown'

class MarkdownTastic extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return <Markdown
            text={this.props.data.text}
            className={classnames(
                's-text',
                'c-markdown',
                'c-markdown--align-' + this.props.data.align,
                'c-markdown--padding-' + this.props.data.padding
            )}
        />
    }
}

MarkdownTastic.propTypes = {
    data: PropTypes.object.isRequired,
}

MarkdownTastic.defaultProps = {
}

export default MarkdownTastic
