/*=========================================================================================
  File Name: moduleCalendarActions.js
  Description: Calendar Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Hospital Solution
  Author: Radianz
  Author URL: https://www.radianzinfotech.com
==========================================================================================*/

import axios from '@/axios.js'
export default {
  fetchInvDashboardReport ({ commit }, payload) {
    return new Promise((resolve, reject) => {
      axios.get('/api/inv_dashboard/report?to_date='+payload.sort.to_date
      +'&from_date='+payload.sort.from_date
      +'&filter='+payload.sort.filter
      +'&org_id='+payload.sort.org_id
      +'&dept='+payload.sort.dept)
        .then((response) => {
          commit('SET_INV_REPORT', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  }, 
}
