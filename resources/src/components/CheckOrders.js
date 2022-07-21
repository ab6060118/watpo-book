// 負責渲染和清理資料
import { Link, withRouter } from 'react-router-dom';
import { withTranslation } from 'react-i18next';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import SweetAlert from 'sweetalert-react';
import OrderInfos from './CheckOrders/OrderInfos';
import LoadingAnimation from './LoadingAnimation';
import toggleLoading from '../dispatchers/toggleLoading';
import setCheckOrdersInfo from '../dispatchers/setCheckOrdersInfo';

const { Row, Col } = ReactBootstrap;

class CheckOrders extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      showAlert: false,
      alertTitle: '',
      alertText: '',
    };

    this.getOrdersError = this.getOrdersError.bind(this);
    this.cancelSuccess = this.cancelSuccess.bind(this);
  }

  getOrdersError() {
    this.setState({
      showAlert: true,
      alertTitle: 'Error',
      alertText: 'errorHint_system',
    });
  }

  cancelSuccess() {
    this.setState({
      showAlert: true,
      alertTitle: 'success',
      alertText: 'orderCanceled',
    });
  }

  render() {
    const {
      t, loading, history, location,
    } = this.props;
    const { showAlert, alertTitle, alertText } = this.state;

    return (
      <>
        <div className="reservationContainer" style={{ marginTop: 16 }}>
          <Row>
            <Col md={12}>
              <p className="prevStap">
                <Link to={{
                  pathname: '/auth',
                  state: {
                    from: location.pathname,
                  },
                }}
                >
                  <span>
                    <i className="fa fa-angle-left" aria-hidden="true" />
                    {` ${t('searchAgain')}`}
                  </span>
                </Link>
              </p>
            </Col>
            <div className="checkOrdersContent" style={{ padding: '16px 0' }}>
              <OrderInfos getOrdersError={this.getOrdersError} cancelSuccess={this.cancelSuccess} />
            </div>
            {loading && <Col md={12}><LoadingAnimation /></Col>}
          </Row>
        </div>
        <SweetAlert
          show={showAlert}
          title={t(alertTitle)}
          text={t(alertText)}
          onConfirm={() => {
            this.setState({ showAlert: false });
            if (alertTitle === 'Error') {
              history.push('/auth');
            }
          }}
        />
      </>
    );
  }
}

const mapStateToProps = (state) => ({
  loading: state.loading,
  checkOrdersInfo: state.checkOrdersInfo,
});

const mapDispatchToProps = (dispatch) => bindActionCreators({
  setCheckOrdersInfo,
  toggleLoading,
}, dispatch);

CheckOrders = withRouter(connect(mapStateToProps, mapDispatchToProps)(CheckOrders));

export default withTranslation()(CheckOrders);
