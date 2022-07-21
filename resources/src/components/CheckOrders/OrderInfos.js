// 負責寫資料(日期,時段)到global state
import { withTranslation } from 'react-i18next';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Button } from 'react-bootstrap';
import { renderToStaticMarkup } from 'react-dom/server';
import SweetAlert from 'sweetalert-react';
import { withRouter } from 'react-router-dom';
import toggleLoading from '../../dispatchers/toggleLoading';
import Alert from '../Reservation/Alert';

const {
  Grid, Row, Col, Table,
} = ReactBootstrap;

class OrdersInfo extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      hint: 'ordersInfoHint',
      orders: [{
        id: 145723,
        shop: '民生會館',
        service: '精油100分按摩 10分沖澡',
        status: 1,
        person: 5,
        start_time: '2022-03-10 14:30:00',
        service_provider: ' 21',
      }],
      id: null,
      showDetail: false,
      detail_data: '',
    };
    this.cancel = this.cancel.bind(this);
    this.getOrders = this.getOrders.bind(this);
    this.confirmCancel = this.confirmCancel.bind(this);
    this.showDetail = this.showDetail.bind(this);
  }

  componentDidMount() {
    this.getOrders();
  }

  getOrders() {
    const that = this;
    const csrf_token = document.querySelector('input[name="_token"]').value;
    that.props.toggleLoading(true);
    // 取得預約資料列表
    axios({
      method: 'get',
      url: '/api/order/list',
      params: {
        name: this.props.checkOrdersInfo.name,
        phone: this.props.checkOrdersInfo.contactNumber,
      },
      headers: { 'X-CSRF-TOKEN': csrf_token },
      responseType: 'json',
    })
      .then((response) => {
        if (response.statusText == 'OK') {
          that.setState({ orders: response.data });
          that.props.toggleLoading(false);
        }
      })
      .catch((error) => {
        console.log(error);
        that.props.toggleLoading(false);
        that.props.getOrdersError();
      });
  }

  showDetail(id) {
    const reservation = this.state.orders[id];
    const { t } = this.props;
    this.setState({
      showDetail: true,
      detail_data: <Alert
        notice={t('reserveNotice2')}
        text={
                `${t('locations')}: ${reservation.shop}\n${
                  t('registrationNumber')}: ${reservation.shop == 1 ? '02 2581-3338' : '02 2570-9393'}\n${
                  t('contactNumber')}: ${this.props.checkOrdersInfo.contactNumber}\n${
                  t('reservatorDate')}: ${reservation.start_time}\n`
                // + "服務: " + serviceName + "\n"
                + `包廂:${reservation.service
                }人數: ${reservation.person} ${reservation.person > 1 ? t('people') : t('person')}\n${
                  t('reserveNotice1')}\n${
                  t('reserveNotice2')}\n${
                  t('reserveNotice3')}\n` + '本店不可攜帶寵物'
}
      />,
    });
  }

  confirmCancel(e) {
    this.setState({
      showAlert: true,
      alertTitle: 'concelConfirm',
      alertText: 'orderCanceledConfirm',
      id: e.target.getAttribute('value'),
    });
  }

  cancel(id) {
    const that = this;
    const csrf_token = document.querySelector('input[name="_token"]').value;
    // id = e.target.getAttribute("value");
    that.props.toggleLoading(true);

    axios({
      method: 'post',
      url: '/api/order/customer/cancel',
      params: {
        name: this.props.checkOrdersInfo.name,
        phone: this.props.checkOrdersInfo.contactNumber,
        order_id: id,
      },
      headers: { 'X-CSRF-TOKEN': csrf_token },
      responseType: 'json',
    })
      .then((response) => {
        if (response.statusText == 'OK') {
          that.props.toggleLoading(false);
          that.props.cancelSuccess();
          that.getOrders();
        }
      })
      .catch((error) => {
        console.log(error);
        that.props.toggleLoading(false);
        that.props.getOrdersError();
      });
  }

  render() {
    const {
      history,
      checkOrdersInfo: { name, contactNumber } = {},
    } = this.props;
    if (name === undefined || contactNumber === undefined) history.push('/auth');

    const { t } = this.props;
    const {
      alertText,
      alertTitle,
      detail_data,
      hint,
      id,
      orders,
      showAlert,
      showDetail,
    } = this.state;
    const ths = [
      '',
      '',
      'branch',
      'time',
      'service',
      'guestNum',
      'operator',
      'action',
    ].map((th, index) => (<th key={index}>{t(th)}</th>));

    return (
      <Grid>
        <Row className="show-grid">
          <Col md={8}>
            <Table responsive striped bordered condensed hover>
              <thead>
                <tr>
                  {ths}
                </tr>
              </thead>
              <tbody>
                {orders.length > 0 ? orders.map((order, index) => {
                  const {
                    id,
                    person,
                    service,
                    service_provider,
                    shop,
                    start_time,
                  } = order || {};
                  return (
                    <tr key={id}>
                      <td className="detail" onClick={() => { this.showDetail(index); }}>內容</td>

                      <td className="cancel" onClick={this.confirmCancel} value={id}>{t('cancel')}</td>
                      <td>{shop}</td>
                      <td>{start_time}</td>
                      <td>{service}</td>
                      <td>{person}</td>
                      <td>{service_provider}</td>

                    </tr>
                  );
                }) : <td colSpan="5"><p>{t(hint)}</p></td>}
              </tbody>
            </Table>
          </Col>
        </Row>
        <SweetAlert
          showCancelButton
          show={showAlert}
          title={t(alertTitle)}
          text={t(alertText)}
          cancelButtonText="No"
          confirmButtonText="Yes"
          onConfirm={() => {
            console.log('comfirm click', this.state);
            this.setState({ showAlert: false });
            this.cancel(id);
            // if (this.alertTitle == "Error") { location.href = "../checkOrders/0" }
          }}
          onCancel={() => {
            this.setState({ showAlert: false });
          }}
        />
        <SweetAlert
          show={showDetail}
          title={t('reserveDetail')}
          html
          text={typeof detail_data === 'object' ? renderToStaticMarkup(detail_data) : detail_data}
          onConfirm={() => {
            this.setState({ showDetail: false });
          }}
        />
      </Grid>
    );
  }
}

const mapStateToProps = (state) => ({
  loading: state.loading,
  checkOrdersInfo: state.checkOrdersInfo,
});

const mapDispatchToProps = (dispatch) => bindActionCreators({
  toggleLoading,
}, dispatch);

OrdersInfo = withRouter(connect(mapStateToProps, mapDispatchToProps)(OrdersInfo));

export default withTranslation()(OrdersInfo);
