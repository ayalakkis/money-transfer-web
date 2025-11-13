import CountUp from 'react-countup'

const AnimatedNumber = ({ value, prefix = '', suffix = '', decimals = 0, duration = 2 }) => {
  return (
    <CountUp
      end={value}
      prefix={prefix}
      suffix={suffix}
      decimals={decimals}
      duration={duration}
      separator=","
    />
  )
}

export default AnimatedNumber

