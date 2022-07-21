export default (dataKey, payload) => {
  switch (dataKey) {
    case 'name':
      return ({
        type: 'SET_CHECKORDERSINFO_NAME',
        payload,
      });
    case 'contactNumber':
      return ({
        type: 'SET_CHECKORDERSINFO_CONTACT_NUMBER',
        payload,
      });
  }
};
