
class RichDisplay extends React.Component {
  constructor(props) {
    super(props);
    this.div = React.createRef();
  }

  renderRichDisplay() {
    var div = this.div.current;
    var config = {
      "container": div,
      "method": "GET",
      "setDims": true
    };
    Object.assign(config, this.props);
    if (typeof config.path !== "string") return;
    if (config.style) delete config.style;
    if (config.className) delete config.className;
    pui.show(config);
  }

  componentDidMount() {
    this.renderRichDisplay();
  }

  componentDidUpdate() {
    this.renderRichDisplay();
  }

  render() {
    var css = {
      position: "relative"
    };
    if (typeof this.props.style === "object") {
      Object.assign(css, this.props.style);
    }
    var divProps = {
      style: css,
      ref: this.div
    }
    if (this.props.className) {
      divProps.className = this.props.className;
    }
    return React.createElement("div", divProps);
  }
}
