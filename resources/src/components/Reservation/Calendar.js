import { withTranslation } from 'react-i18next';

class Calendar extends React.Component {
  constructor(props) {
    super(props);
    const date = new Date();
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    const lastDayOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    const inputtedDate = this.props.date ? this.props.date.split('/') : null;

    this.state = {
      displayingYear: inputtedDate ? +inputtedDate[0] : date.getFullYear(),
      displayingMonth: inputtedDate ? inputtedDate[1] : date.getMonth() + 1, // 1-based
      firstWeekDay: firstDay.getDay(), // 1-based
      dayNum: lastDayOfMonth, // 0-based, 0=>禮拜天, 1=>禮拜一...
    };

    this.changeMonth = this.changeMonth.bind(this);
    this.selectDay = this.selectDay.bind(this);
  }

  changeMonth(date) {
    // check if date if later than current date
    if (date.getTime() < new Date(new Date().getFullYear(), new Date().getMonth()).getTime()) return;
    if (date.getMonth > 11 || date.getMonth < 0) return;

    this.setState({ selectedDay: -1 });
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    const lastDayOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

    // set local state
    this.setState({
      displayingYear: date.getFullYear(),
      displayingMonth: date.getMonth() + 1,
      firstWeekDay: firstDay.getDay(),
      dayNum: lastDayOfMonth,
    });

    // // clear current displaying time periods
    this.props.changeMonthHandle();
  }

  selectDay(event) {
    // send in year, month, day arguments
    this.props.selectDayHandle(this.state.displayingYear, +this.state.displayingMonth, +event.target.innerHTML);
  }

  render() {
    const { t } = this.props; const
      selectedDay = this.props.date ? +this.props.date.split('/')[2] : -1;

    const months = [t('jan'), t('feb'), t('mar'), t('apr'), t('may'), t('jun'), t('jul'), t('aug'), t('sep'), t('oct'), t('nov'), t('dec')];
    const weekDays = [t('mon'), t('tue'), t('wed'), t('thu'), t('fri'), t('sat'), t('sun')];
    const unit = 100 / 7;
    const isCurrentMonth = (this.state.displayingMonth == new Date().getMonth() + 1) && (this.state.displayingYear == new Date().getFullYear());
    const today = new Date(new Date().getTime()).getDate();
    const yesterday = new Date(new Date().getTime() - 24 * 60 * 60 * 1000).getDate();
    const days = []; const spanStyle = { display: 'inline-block', width: `${unit}%` };
    const firstDayStyle = {
      display: 'inline-block',
      width: `${unit}%`,
      marginLeft: this.state.firstWeekDay ? `${(this.state.firstWeekDay - 1) * unit}%`
        : `${unit * 6}%`,
    };

    for (let i = 1; i <= this.state.dayNum; i++) {
      const isPastDay = isCurrentMonth && i < today;
      if (i === 1) days.push(<span key={i} className={i === selectedDay ? 'day selectedDay' : (isPastDay ? 'day pastDay' : 'day')} style={firstDayStyle} onClick={isPastDay ? null : this.selectDay}>{i}</span>);
      else days.push(<span key={i} className={i === selectedDay ? 'day selectedDay' : (isPastDay ? 'day pastDay' : 'day')} style={spanStyle} onClick={isPastDay ? null : this.selectDay}>{i}</span>);
    }

    return (
      <div className="calendar">
        <p className="year">{this.state.displayingYear}</p>
        <p className="month">
          <span
            onClick={() => {
              const prevMonth = this.state.displayingMonth - 2; // 0-based

              this.changeMonth(new Date(
                prevMonth < 0 ? this.state.displayingYear - 1 : this.state.displayingYear,
                prevMonth < 0 ? prevMonth + 12 : prevMonth,
              ));
            }}
            className="prev"
          >
            <i className="fa fa-angle-left" aria-hidden="true" />
          </span>
          {months[this.state.displayingMonth - 1]}
          <span
            onClick={() => {
              const nextMonth = this.state.displayingMonth; // 0-based

              this.changeMonth(new Date(
                nextMonth >= 12 ? this.state.displayingYear + 1 : this.state.displayingYear,
                nextMonth >= 12 ? 0 : nextMonth,
              ));
            }}
            className="next"
          >
            <i className="fa fa-angle-right" aria-hidden="true" />
          </span>
        </p>
        <p className="weekDays">
          {weekDays.map((day, index) => (<span key={index} style={spanStyle}>{day}</span>))}
        </p>
        <p className="days">{days}</p>
      </div>
    );
  }
}

export default withTranslation()(Calendar);
