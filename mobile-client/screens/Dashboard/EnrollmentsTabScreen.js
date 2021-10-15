import * as React from 'react'
import { useState, useEffect } from 'react'
import {View, Platform, ScrollView, StyleSheet, Dimensions, ActivityIndicator} from 'react-native'
import { ApplicationProvider, Layout, Text, Select, SelectItem, Button, RadioGroup, Radio, List, ListItem } from '@ui-kitten/components'
import * as eva from '@eva-design/eva'

import style from './../../common/style'

export default EnrollmentsTabScreen = (props) => {
  const [selectedYearIndex, setSelectedYear] = useState()
  const [selectedMonthIndex, setSelectedMonth] = useState()
  const [selectedPaymentState, setSelectedPaymentState] = useState(0)
  const [years, setYears] = useState([
    {
      label: 2020,
      value: 2020
    },
    {
      label: 2021,
      value: 2021
    },
    {
      label: 2022,
      value: 2022
    }
  ])
  const [months, setMonths] = useState([
    {
      label: 'January',
      value: '01'
    },
    {
      label: 'February',
      value: '02'
    },
    {
      label: 'March',
      value: '03'
    },
    {
      label: 'April',
      value: '04'
    },
    {
      label: 'May',
      value: '05'
    },
    {
      label: 'June',
      value: '06'
    },
    {
      label: 'July',
      value: '07'
    },
    {
      label: 'August',
      value: '08'
    },
    {
      label: 'September',
      value: '09'
    },
    {
      label: 'Octomer',
      value: '10'
    },
    {
      label: 'November',
      value: '11'
    },
    {
      label: 'December',
      value: '12'
    }
  ])
  const [fetch, setFetched] = useState(false)


  useEffect(() => {
    getInitialData()
  }, [])
  
  const getInitialData = () => {
    setTimeout(() => setFetched(true), 2000); // initial data fetch
  }

  const getVal = () => {
    console.log(selectedYearIndex)
    console.log(selectedMonthIndex)
  }

  const renderYears = (obj) => (
    <SelectItem title={obj.label}/>
  )
  
  const displayYear = years.map((obj, i) => {
    if (selectedYearIndex && (i === selectedYearIndex.row)) {
      return obj.label
    }
  })
  
  const displayMonth = months.map((obj, i) => {
    if (selectedMonthIndex && (i === selectedMonthIndex.row)) {
      return obj.label
    }
  })
  
  const data = new Array(8).fill({
    title: 'Title for Item',
    description: 'Description for Item',
  })

  const renderItemAccessory = (props) => (
    <Button
      status='primary'
      onPress={() => { getVal() }}
    >
      VIEW LESSON
    </Button>
  )

  const renderItem = ({ item, index }) => (
    <ListItem
    style={{paddingHorizontal: 0}}
    title={`${item.title} ${index + 1}`}
    description={`${item.description} ${index + 1}`}
    accessoryRight={selectedPaymentState === 0 ? renderItemAccessory : ''}
    />
  )

  return ( !fetch ? 
    ( <View style={{alignItems: 'center', justifyContent: 'center', flex: 1}}>
      <ActivityIndicator/>
    </View> ) : (
    <ApplicationProvider {...eva} theme={eva.dark}>
      <Layout style={{ paddingHorizontal: 20, flex: 1, alignItems: 'stretch' }}>
        <Text category='h1'></Text>
        <View>
          <Select
          style={style.input}
          label='Select year'
          placeholder='Select enrollment year'
          value={displayYear}
          selectedIndex={selectedYearIndex}
          onSelect={index => setSelectedYear(index)}>
            {years.map(renderYears)}
          </Select>
          {selectedYearIndex && (
            <Select
            style={style.input}
            value={displayMonth}
            label='Select month'
            placeholder='Select enrollment month'
            selectedIndex={selectedMonthIndex}
            onSelect={index => setSelectedMonth(index)}>
            {months.map(renderYears)}
            </Select>
          )}
          <RadioGroup
          style={{flexDirection: 'row', justifyContent: 'space-between', marginBottom: 15}}
          selectedIndex={selectedPaymentState}
          onChange={index => setSelectedPaymentState(index)}>
            <Radio
              status='success'
            >
              Approved
            </Radio>
            <Radio
              status='basic'
            >
              Pending
            </Radio>
            <Radio
              status='danger'
            >
              Rejected
            </Radio>
          </RadioGroup>
          <Button
          style={style.input}
          status='success'
          onPress={() => { getVal() }}
          >
            APPLY FILTER
          </Button>
        </View>
        <View>
          <ScrollView style={{height: Dimensions.get('window').height - 254}}>
            <List
              data={data}
              renderItem={renderItem}
            />
          </ScrollView>
        </View>
      </Layout>
    </ApplicationProvider>
  ))
}

const styles = StyleSheet.create({
  scrollView: {
    backgroundColor: 'pink',
    marginHorizontal: 20
  }
});