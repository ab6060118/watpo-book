import { withTranslation } from 'react-i18next';
import Button from './Button';

const { Col } = ReactBootstrap;
const { Row } = ReactBootstrap;
const { FormGroup } = ReactBootstrap;
const { FormControl } = ReactBootstrap;
const { ControlLabel } = ReactBootstrap;

class CheckService extends React.Component {
  constructor(props) {
    super(props);
    this.setReservation = this.setReservation.bind(this);
  }

  componentDidMount() {
    if (!this.props.sourceData.services || !this.props.sourceData.shops) {
      const that = this;
      const csrf_token = document.querySelector('input[name="_token"]').value;
      let finished = 0;

      this.props.toggleLoading();

      axios({
        method: 'get',
        url: '../api/shop_list',
        headers: { 'X-CSRF-TOKEN': csrf_token },
        responseType: 'json',
      })
        .then((response) => {
          if (response.statusText == 'OK') {
            that.props.setSourceData({ shops: response.data });
            finished += 1;
            if (finished == 2) {
              if (that.props.loading) that.props.toggleLoading();
              that.props.setReservation({ shop: 1 });
            }
          }
        })
        .catch((error) => {
          console.log(error);
          if (finished == 2) that.props.toggleLoading();
          that.props.showErrorPopUp();
        });

      axios({
        method: 'get',
        url: '../api/service_list',
        responseType: 'json',
        headers: { 'X-CSRF-TOKEN': csrf_token },
      })
        .then((response) => {
          if (response.statusText == 'OK') {
            that.props.setSourceData({ services: response.data });
            finished += 1;
            if (finished == 2) {
              if (that.props.loading) that.props.toggleLoading();
              that.props.setReservation({ shop: 1 });
            }
          }
        })
        .catch((error) => {
          console.log(error);
          if (finished == 2) that.props.toggleLoading();
          that.props.showErrorPopUp();
        });
    }
  }

  setReservation(event) {
    const el = event.target;
    const group = 'shop';
    const index = +el.value;

    const data = {};
    data[group] = index;
    if (index === 5) data.shower = true;
    else data.shower = false;
    this.props.setReservation(data);
    this.props.nextStep();
  }

  render() {
    const { t } = this.props;
    const { reservation } = this.props;
    const { sourceData } = this.props;
    const disabled = (!reservation.shop) || this.props.loading;

    return (
      <div style={{ paddingTop: '5px' }}>
        <FormGroup>
          <ControlLabel bsClass="control-label branch">{t('branch')}</ControlLabel>
          <Row>
            {sourceData.shops && sourceData.shops.map((shop, index) => (<Col md={6} sm={6} xs={6}><FormControl componentClass="button" currentStep={0} value={shop.id} onClick={this.setReservation}>{shop.name}</FormControl></Col>))}
          </Row>
        </FormGroup>
      </div>
    );
  }
}

export default withTranslation()(CheckService);
