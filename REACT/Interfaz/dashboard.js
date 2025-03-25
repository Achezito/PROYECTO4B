import React from 'react';
import { ScrollView, StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import Icon from 'react-native-vector-icons/MaterialIcons'; // Asegúrate de instalar react-native-vector-icons
 
 export default function Dashboard({ navigation }) {
   const sections = [
     { title: 'Proveedores', icon: 'local-shipping', screen: 'ProveedoresScreen' },
     { title: 'Suministros', icon: 'inventory', screen: 'SuministrosScreen' },
     { title: 'Almacenes', icon: 'store', screen: 'InterfazWarehouse' },
     { title: 'Órdenes', icon: 'assignment', screen: 'OrdersScreen' },
     { title: 'Reportes', icon: 'bar-chart', screen: 'ReportsScreen' },
     { title: 'Configuración', icon: 'settings', screen: 'SettingsScreen' },
   ];
 
   return (
     <ScrollView contentContainerStyle={styles.ScrollView_container}>
       <Text style={styles.ScrollView_title}>Dashboard</Text>
       <View style={styles.grid}>
         {sections.map((section, index) => (
           <TouchableOpacity
             key={index}
             style={styles.card}
             onPress={() => navigation.navigate(section.screen)}
           >
             <Icon name={section.icon} size={40} color="#4CAF50" />
             <Text style={styles.cardText}>{section.title}</Text>
           </TouchableOpacity>
         ))}
       </View>
     </ScrollView>
   );
 }
 
 const styles = StyleSheet.create({
  ScrollView_container: {
    width: "100%",
    height: "100%",
     backgroundColor: '#f5f5f5',
     alignItems: 'center',
   },
   ScrollView_title: {
     fontSize: 28,
     fontWeight: 'bold',
     color: '#333',
     marginBottom: 20,
   },
   grid: {
     flexDirection: 'row',
     flexWrap: 'wrap',
     justifyContent: 'space-between',
   },
   card: {
     width: '45%',
     backgroundColor: '#fff',
     borderRadius: 10,
     padding: 20,
     marginBottom: 20,
     alignItems: 'center',
     shadowColor: '#000',
     shadowOffset: { width: 0, height: 2 },
     shadowOpacity: 0.1,
     shadowRadius: 5,
     elevation: 5,
   },
   cardText: {
     marginTop: 10,
     fontSize: 16,
     fontWeight: 'bold',
     color: '#333',
     textAlign: 'center',
   },
 });